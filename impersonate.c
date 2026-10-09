/*
   +----------------------------------------------------------------------+
   | Copyright © The PHP Group and Contributors.                          |
   +----------------------------------------------------------------------+
   | This source file is subject to the Modified BSD License that is      |
   | bundled with this package in the file LICENSE, and is available      |
   | through the World Wide Web at <https://www.php.net/license/>.        |
   |                                                                      |
   | SPDX-License-Identifier: BSD-3-Clause                                |
   +----------------------------------------------------------------------+
*/

/* Everything specific to curl-impersonate lives here, so interface.c,
 * multi.c, share.c and curl_file.c stay close to php-src's ext/curl. */

#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

#include "php.h"
#include "zend_smart_str.h"

#include <curl/curl.h>
#include <curl/easy.h>

#include "curl_private.h"

/* ext/curl's upload classes, when ext/curl is loaded. Resolved at MINIT,
 * which runs after ext/curl's MINIT thanks to the optional module dependency. */
static zend_class_entry *ext_curl_file_ce = NULL;
static zend_class_entry *ext_curl_string_file_ce = NULL;

static zend_class_entry *lookup_internal_class(const char *lcname, size_t lcname_len)
{
	zend_class_entry *ce = zend_hash_str_find_ptr(CG(class_table), lcname, lcname_len);

	return (ce && ce->type == ZEND_INTERNAL_CLASS) ? ce : NULL;
}

/* Guards against the dynamic linker binding our curl_* calls to another
 * libcurl in the process (e.g. the one ext/curl uses), which happens with
 * flat symbol namespaces or without RTLD_DEEPBIND. */
zend_result php_curl_impersonate_check_runtime_library(void)
{
	const curl_version_info_data *d;

	if (strstr(LIBCURL_VERSION, "IMPERSONATE") == NULL) {
		/* Built against a release that does not tag its version */
		return SUCCESS;
	}

	d = curl_version_info(CURLVERSION_NOW);
	if (d && d->version && strstr(d->version, "IMPERSONATE") != NULL) {
		return SUCCESS;
	}

	php_error_docref(NULL, E_CORE_WARNING,
		"curl_impersonate was built against libcurl %s, but its libcurl calls resolve to libcurl %s. "
		"Another libcurl loaded in this process is interposing libcurl-impersonate's symbols",
		LIBCURL_VERSION, d && d->version ? d->version : "(unknown)");

	return FAILURE;
}

/* "CURLOPT_URL" -> "CURLIMPOPT_URL", "CURLE_OK" -> "CURLIMPE_OK" */
static zend_string *constant_fallback_name(zend_string *name)
{
	const size_t old_prefix_len = sizeof("CURL") - 1;
	const size_t new_prefix_len = sizeof("CURLIMP") - 1;
	zend_string *fallback;

	if (!zend_string_starts_with_literal(name, "CURL")) {
		return NULL;
	}

	/* Persistent: it outlives the request as a constant name */
	fallback = zend_string_alloc(ZSTR_LEN(name) - old_prefix_len + new_prefix_len, true);
	memcpy(ZSTR_VAL(fallback), "CURLIMP", new_prefix_len);
	memcpy(ZSTR_VAL(fallback) + new_prefix_len, ZSTR_VAL(name) + old_prefix_len, ZSTR_LEN(name) - old_prefix_len + 1);

	return fallback;
}

/* Registers the constants declared in curl_impersonate.stub.php without
 * clashing with ext/curl. Constants that ext/curl already defines with the
 * same value are left to ext/curl (both come from the libcurl ABI). Should a
 * value ever differ, ours is registered with "CURL" replaced by "CURLIMP". */
void php_curl_impersonate_register_constants(void (*register_symbols)(int), int module_number)
{
	HashTable *global_constants = EG(zend_constants);
	HashTable own_constants;
	zend_string *name;
	zend_constant *c;

	ext_curl_file_ce = lookup_internal_class(ZEND_STRL("curlfile"));
	ext_curl_string_file_ce = lookup_internal_class(ZEND_STRL("curlstringfile"));

	/* Let the generated register_curl_impersonate_symbols() fill a private
	 * table, then merge that into the global one. */
	zend_hash_init(&own_constants, 1024, NULL, NULL, true);
	EG(zend_constants) = &own_constants;
	register_symbols(module_number);
	EG(zend_constants) = global_constants;

	ZEND_HASH_MAP_FOREACH_STR_KEY_PTR(&own_constants, name, c) {
		zend_constant *existing = zend_hash_find_ptr(global_constants, name);

		if (!existing) {
			zend_hash_add_new_ptr(global_constants, name, c);
			continue;
		}

		if (!zend_is_identical(&existing->value, &c->value)) {
			zend_string *fallback = constant_fallback_name(name);

			if (fallback && !zend_hash_exists(global_constants, fallback)) {
				zend_string_release(c->name);
				c->name = zend_new_interned_string(fallback);
				zend_hash_add_new_ptr(global_constants, c->name, c);
				continue;
			}
			if (fallback) {
				zend_string_release(fallback);
			}
		}

		zend_string_release(c->name);
		pefree(c, true);
	} ZEND_HASH_FOREACH_END();

	zend_hash_destroy(&own_constants);
}

static bool file_object_of(const zval *zv, zend_class_entry *own_ce, zend_class_entry *ext_ce)
{
	if (Z_TYPE_P(zv) != IS_OBJECT) {
		return false;
	}

	return instanceof_function(Z_OBJCE_P(zv), own_ce)
		|| (ext_ce && instanceof_function(Z_OBJCE_P(zv), ext_ce));
}

/* CURLImpersonateFile, or ext/curl's CURLFile */
bool php_curl_impersonate_is_file_object(const zval *zv)
{
	return file_object_of(zv, curl_impersonate_CURLFile_class, ext_curl_file_ce);
}

/* CURLImpersonateStringFile, or ext/curl's CURLStringFile */
bool php_curl_impersonate_is_string_file_object(const zval *zv)
{
	return file_object_of(zv, curl_impersonate_CURLStringFile_class, ext_curl_string_file_ce);
}

/* Browsers ask for compressed responses, and impersonation sends their
 * Accept-Encoding header, but libcurl only decompresses a body when
 * CURLOPT_ACCEPT_ENCODING is set. While the browser's headers are in use that
 * option's value is never sent (libcurl skips its own Accept-Encoding header
 * when one is already present), so default it to "" to turn decoding on.
 * A value set by the user, including null, is never replaced. Without the
 * browser's headers nothing is changed, so libcurl does not add an
 * Accept-Encoding header of its own. */
static void apply_accept_encoding_default(php_curl *ch, bool default_headers)
{
	if (default_headers && !ch->accept_encoding_set_by_user) {
		curl_easy_setopt(ch->cp, CURLOPT_ACCEPT_ENCODING, "");
	}
}

void php_curl_impersonate_note_setopt(php_curl *ch, zend_long option)
{
	if (option == CURLOPT_ACCEPT_ENCODING) {
		ch->accept_encoding_set_by_user = true;
	}
}

static zend_result impersonate(php_curl *ch, const char *target, bool default_headers)
{
	CURLcode error = curl_easy_impersonate(ch->cp, target, default_headers ? 1 : 0);

	SAVE_CURL_ERROR(ch, error);

	if (error != CURLE_OK) {
		return FAILURE;
	}

	apply_accept_encoding_default(ch, default_headers);

	return SUCCESS;
}

/* Applies curl_impersonate.target, the INI equivalent of CURL_IMPERSONATE */
zend_result php_curl_impersonate_apply_default_target(php_curl *ch)
{
	const char *target = CURL_IMPERSONATE_G(default_target);

	if (!target || !*target) {
		/* libcurl-impersonate applies the CURL_IMPERSONATE environment
		 * variable in curl_easy_init()/curl_easy_reset() itself */
		if (getenv("CURL_IMPERSONATE")) {
			const char *headers = getenv("CURL_IMPERSONATE_HEADERS");

			apply_accept_encoding_default(ch, !headers || strcasecmp(headers, "no") != 0);
		}
		return SUCCESS;
	}

	if (impersonate(ch, target, CURL_IMPERSONATE_G(default_headers)) == FAILURE) {
		php_error_docref(NULL, E_WARNING, "Could not impersonate \"%s\" (curl_impersonate.target): %s",
			target, curl_easy_strerror(ch->err.no));
		return FAILURE;
	}

	return SUCCESS;
}

/* Options that curl-impersonate adds on top of libcurl (CURLOPT_IMPERSONATE,
 * CURLOPT_HTTP2_SETTINGS, ...) use option numbers from 999 upwards. They are
 * all plain long/string/slist options, so they are dispatched on the type
 * libcurl reports for them instead of being listed in _php_curl_setopt(). */
bool php_curl_impersonate_is_extra_option(zend_long option)
{
	if (option < 0 || option > INT_MAX || (option % 10000) < 999) {
		return false;
	}

	return curl_easy_option_by_id((CURLoption) option) != NULL;
}

static const char *option_name(zend_long option)
{
	const struct curl_easyoption *opt = curl_easy_option_by_id((CURLoption) option);

	return opt ? opt->name : "UNKNOWN_OPTION";
}

static zend_result setopt_string(php_curl *ch, zend_long option, zval *zvalue)
{
	CURLcode error;

	if (Z_ISNULL_P(zvalue)) {
		error = curl_easy_setopt(ch->cp, (CURLoption) option, NULL);
	} else {
		zend_string *tmp_str;
		zend_string *str = zval_get_tmp_string(zvalue, &tmp_str);

		if (zend_str_has_nul_byte(str)) {
			zend_tmp_string_release(tmp_str);
			zend_value_error("%s(): cURL option CURLOPT_%s must not contain any null bytes", get_active_function_name(), option_name(option));
			return FAILURE;
		}

		error = curl_easy_setopt(ch->cp, (CURLoption) option, ZSTR_VAL(str));
		zend_tmp_string_release(tmp_str);
	}

	SAVE_CURL_ERROR(ch, error);

	return error == CURLE_OK ? SUCCESS : FAILURE;
}

/* Same semantics as the CURLOPT_HTTPHEADER branch of _php_curl_setopt() */
static zend_result setopt_slist(php_curl *ch, zend_long option, zval *zvalue)
{
	zval *current;
	zend_string *val, *tmp_val;
	struct curl_slist *slist = NULL;
	CURLcode error;

	if (Z_TYPE_P(zvalue) != IS_ARRAY) {
		zend_type_error("%s(): The CURLOPT_%s option must have an array value", get_active_function_name(), option_name(option));
		return FAILURE;
	}

	ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(zvalue), current) {
		ZVAL_DEREF(current);
		val = zval_get_tmp_string(current, &tmp_val);

		if (zend_str_has_nul_byte(val)) {
			curl_slist_free_all(slist);
			zend_tmp_string_release(tmp_val);
			zend_value_error("%s(): cURL option CURLOPT_%s must not contain any null bytes", get_active_function_name(), option_name(option));
			return FAILURE;
		}

		struct curl_slist *new_slist = curl_slist_append(slist, ZSTR_VAL(val));
		zend_tmp_string_release(tmp_val);
		if (!new_slist) {
			curl_slist_free_all(slist);
			php_error_docref(NULL, E_WARNING, "Could not build curl_slist");
			return FAILURE;
		}
		slist = new_slist;
	} ZEND_HASH_FOREACH_END();

	if (slist) {
		if ((*ch->clone) == 1) {
			zend_hash_index_update_ptr(&ch->to_free->slist, option, slist);
		} else {
			zend_hash_next_index_insert_ptr(&ch->to_free->slist, slist);
		}
	}

	error = curl_easy_setopt(ch->cp, (CURLoption) option, slist);
	SAVE_CURL_ERROR(ch, error);

	return error == CURLE_OK ? SUCCESS : FAILURE;
}

/* curl_impersonate_reset(): curl_easy_reset() cleared every option */
void php_curl_impersonate_reset(php_curl *ch)
{
	ch->accept_encoding_set_by_user = false;
	php_curl_impersonate_apply_default_target(ch);
}

#if HAVE_DECL_CURLOPT_IMPERSONATE
/* "target" or "target:yes" use the browser's default headers, "target:no" does not */
static zend_result setopt_impersonate(php_curl *ch, zval *zvalue)
{
	zend_string *tmp_str;
	zend_string *str;
	bool default_headers;

	if (setopt_string(ch, CURLOPT_IMPERSONATE, zvalue) == FAILURE) {
		return FAILURE;
	}

	str = zval_get_tmp_string(zvalue, &tmp_str);
	default_headers = !(ZSTR_LEN(str) >= sizeof(":no") - 1
		&& memcmp(ZSTR_VAL(str) + ZSTR_LEN(str) - (sizeof(":no") - 1), ":no", sizeof(":no") - 1) == 0);
	zend_tmp_string_release(tmp_str);

	apply_accept_encoding_default(ch, default_headers);

	return SUCCESS;
}
#endif

zend_result php_curl_impersonate_setopt_extra(php_curl *ch, zend_long option, zval *zvalue)
{
	const struct curl_easyoption *opt = curl_easy_option_by_id((CURLoption) option);
	CURLcode error;

	ZEND_ASSERT(opt != NULL);

#if HAVE_DECL_CURLOPT_IMPERSONATE
	if (option == CURLOPT_IMPERSONATE) {
		return setopt_impersonate(ch, zvalue);
	}
#endif

	switch (opt->type) {
		case CURLOT_LONG:
		case CURLOT_VALUES:
			error = curl_easy_setopt(ch->cp, (CURLoption) option, (long) zval_get_long(zvalue));
			break;

		case CURLOT_OFF_T:
			error = curl_easy_setopt(ch->cp, (CURLoption) option, (curl_off_t) zval_get_long(zvalue));
			break;

		case CURLOT_STRING:
			return setopt_string(ch, option, zvalue);

		case CURLOT_SLIST:
			return setopt_slist(ch, option, zvalue);

		default:
			zend_value_error("%s(): cURL option CURLOPT_%s is not supported", get_active_function_name(), opt->name);
			return FAILURE;
	}

	SAVE_CURL_ERROR(ch, error);

	return error == CURLE_OK ? SUCCESS : FAILURE;
}

/* {{{ Impersonate a browser, see curl_easy_impersonate() */
PHP_FUNCTION(curl_impersonate_impersonate)
{
	zval        *zid;
	php_curl    *ch;
	zend_string *target;
	bool        default_headers = true;

	ZEND_PARSE_PARAMETERS_START(2, 3)
		Z_PARAM_OBJECT_OF_CLASS(zid, curl_impersonate_ce)
		Z_PARAM_PATH_STR(target)
		Z_PARAM_OPTIONAL
		Z_PARAM_BOOL(default_headers)
	ZEND_PARSE_PARAMETERS_END();

	ch = Z_CURL_P(zid);

	RETURN_BOOL(impersonate(ch, ZSTR_VAL(target), default_headers) == SUCCESS);
}
/* }}} */
