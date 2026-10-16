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

/* libcurl-impersonate exports the same curl_* symbols as the system libcurl
 * that ext/curl uses. On Linux, PHP 8.5+ (and Debian's PHP 8.4) load
 * extensions into one global symbol scope, so whichever libcurl is loaded
 * first would serve every curl_* call in the process.
 *
 * On Unix the extension is therefore not linked against libcurl-impersonate.
 * php_curl_impersonate_api_load() dlopen()s it into a private scope instead,
 * and the macros below turn every curl_* call into a call through the
 * function table it fills. On Windows each DLL has its own imports, so the
 * library is linked normally there.
 *
 * Every libcurl function the extension calls must be listed in
 * PHP_CURL_IMPERSONATE_FUNCTIONS. On Unix, check with:
 *   nm -u modules/curl_impersonate.so | grep curl_
 * which must print nothing. */

#ifndef PHP_CURL_IMPERSONATE_API_H
#define PHP_CURL_IMPERSONATE_API_H

#include "php.h"

/* All of libcurl's declarations must precede the macros below */
#include <curl/curl.h>

#ifndef PHP_WIN32
# define PHP_CURL_IMPERSONATE_DLOPEN 1
#endif

#define PHP_CURL_IMPERSONATE_FUNCTIONS(X) \
	X(curl_easy_cleanup) \
	X(curl_easy_duphandle) \
	X(curl_easy_escape) \
	X(curl_easy_getinfo) \
	X(curl_easy_impersonate) \
	X(curl_easy_init) \
	X(curl_easy_option_by_id) \
	X(curl_easy_pause) \
	X(curl_easy_perform) \
	X(curl_easy_reset) \
	X(curl_easy_setopt) \
	X(curl_easy_strerror) \
	X(curl_easy_unescape) \
	X(curl_easy_upkeep) \
	X(curl_free) \
	X(curl_global_cleanup) \
	X(curl_global_init) \
	X(curl_mime_addpart) \
	X(curl_mime_data) \
	X(curl_mime_data_cb) \
	X(curl_mime_filename) \
	X(curl_mime_free) \
	X(curl_mime_init) \
	X(curl_mime_name) \
	X(curl_mime_type) \
	X(curl_multi_add_handle) \
	X(curl_multi_cleanup) \
	X(curl_multi_info_read) \
	X(curl_multi_init) \
	X(curl_multi_perform) \
	X(curl_multi_remove_handle) \
	X(curl_multi_setopt) \
	X(curl_multi_strerror) \
	X(curl_multi_wait) \
	X(curl_pushheader_bynum) \
	X(curl_share_cleanup) \
	X(curl_share_init) \
	X(curl_share_setopt) \
	X(curl_share_strerror) \
	X(curl_slist_append) \
	X(curl_slist_free_all) \
	X(curl_version_info)

/* Loads libcurl-impersonate. `path` overrides the library found by
 * configure; NULL or "" uses that one. On failure, returns FAILURE and sets
 * `error` to a message (static storage). */
zend_result php_curl_impersonate_api_load(const char *path, const char **error);

/* The file the library was loaded from, for phpinfo() */
const char *php_curl_impersonate_api_library(void);

#ifdef PHP_CURL_IMPERSONATE_DLOPEN

/* curl/typecheck-gcc.h wraps these in macros of its own */
# undef curl_easy_setopt
# undef curl_easy_getinfo
# undef curl_multi_setopt
# undef curl_share_setopt

/* Members are prefixed so that pasting them never expands the macros below */
# define PHP_CURL_IMPERSONATE_API_MEMBER(name) __typeof__(name) *fn_ ## name;
typedef struct {
	PHP_CURL_IMPERSONATE_FUNCTIONS(PHP_CURL_IMPERSONATE_API_MEMBER)
} php_curl_impersonate_api_t;
# undef PHP_CURL_IMPERSONATE_API_MEMBER

extern php_curl_impersonate_api_t php_curl_impersonate_api;

# define curl_easy_cleanup        (*php_curl_impersonate_api.fn_curl_easy_cleanup)
# define curl_easy_duphandle      (*php_curl_impersonate_api.fn_curl_easy_duphandle)
# define curl_easy_escape         (*php_curl_impersonate_api.fn_curl_easy_escape)
# define curl_easy_getinfo        (*php_curl_impersonate_api.fn_curl_easy_getinfo)
# define curl_easy_impersonate    (*php_curl_impersonate_api.fn_curl_easy_impersonate)
# define curl_easy_init           (*php_curl_impersonate_api.fn_curl_easy_init)
# define curl_easy_option_by_id   (*php_curl_impersonate_api.fn_curl_easy_option_by_id)
# define curl_easy_pause          (*php_curl_impersonate_api.fn_curl_easy_pause)
# define curl_easy_perform        (*php_curl_impersonate_api.fn_curl_easy_perform)
# define curl_easy_reset          (*php_curl_impersonate_api.fn_curl_easy_reset)
# define curl_easy_setopt         (*php_curl_impersonate_api.fn_curl_easy_setopt)
# define curl_easy_strerror       (*php_curl_impersonate_api.fn_curl_easy_strerror)
# define curl_easy_unescape       (*php_curl_impersonate_api.fn_curl_easy_unescape)
# define curl_easy_upkeep         (*php_curl_impersonate_api.fn_curl_easy_upkeep)
# define curl_free                (*php_curl_impersonate_api.fn_curl_free)
# define curl_global_cleanup      (*php_curl_impersonate_api.fn_curl_global_cleanup)
# define curl_global_init         (*php_curl_impersonate_api.fn_curl_global_init)
# define curl_mime_addpart        (*php_curl_impersonate_api.fn_curl_mime_addpart)
# define curl_mime_data           (*php_curl_impersonate_api.fn_curl_mime_data)
# define curl_mime_data_cb        (*php_curl_impersonate_api.fn_curl_mime_data_cb)
# define curl_mime_filename       (*php_curl_impersonate_api.fn_curl_mime_filename)
# define curl_mime_free           (*php_curl_impersonate_api.fn_curl_mime_free)
# define curl_mime_init           (*php_curl_impersonate_api.fn_curl_mime_init)
# define curl_mime_name           (*php_curl_impersonate_api.fn_curl_mime_name)
# define curl_mime_type           (*php_curl_impersonate_api.fn_curl_mime_type)
# define curl_multi_add_handle    (*php_curl_impersonate_api.fn_curl_multi_add_handle)
# define curl_multi_cleanup       (*php_curl_impersonate_api.fn_curl_multi_cleanup)
# define curl_multi_info_read     (*php_curl_impersonate_api.fn_curl_multi_info_read)
# define curl_multi_init          (*php_curl_impersonate_api.fn_curl_multi_init)
# define curl_multi_perform       (*php_curl_impersonate_api.fn_curl_multi_perform)
# define curl_multi_remove_handle (*php_curl_impersonate_api.fn_curl_multi_remove_handle)
# define curl_multi_setopt        (*php_curl_impersonate_api.fn_curl_multi_setopt)
# define curl_multi_strerror      (*php_curl_impersonate_api.fn_curl_multi_strerror)
# define curl_multi_wait          (*php_curl_impersonate_api.fn_curl_multi_wait)
# define curl_pushheader_bynum    (*php_curl_impersonate_api.fn_curl_pushheader_bynum)
# define curl_share_cleanup       (*php_curl_impersonate_api.fn_curl_share_cleanup)
# define curl_share_init          (*php_curl_impersonate_api.fn_curl_share_init)
# define curl_share_setopt        (*php_curl_impersonate_api.fn_curl_share_setopt)
# define curl_share_strerror      (*php_curl_impersonate_api.fn_curl_share_strerror)
# define curl_slist_append        (*php_curl_impersonate_api.fn_curl_slist_append)
# define curl_slist_free_all      (*php_curl_impersonate_api.fn_curl_slist_free_all)
# define curl_version_info        (*php_curl_impersonate_api.fn_curl_version_info)

#endif /* PHP_CURL_IMPERSONATE_DLOPEN */

#endif /* PHP_CURL_IMPERSONATE_API_H */
