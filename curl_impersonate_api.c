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

/* Loads libcurl-impersonate into a private symbol scope, see
 * curl_impersonate_api.h */

#ifndef _GNU_SOURCE
# define _GNU_SOURCE /* RTLD_DEEPBIND */
#endif

#ifdef HAVE_CONFIG_H
# include <config.h>
#endif

#include "php.h"
#include "curl_impersonate_api.h"

#ifdef PHP_CURL_IMPERSONATE_DLOPEN

#include <dlfcn.h>

php_curl_impersonate_api_t php_curl_impersonate_api;

static const char *loaded_library;

static void *open_library(const char *path)
{
	/* RTLD_LOCAL keeps libcurl-impersonate's symbols (curl_*, and the
	 * nghttp2, c-ares and zstd it bundles) out of the global scope, so
	 * ext/curl's libcurl never binds to them. RTLD_DEEPBIND makes
	 * libcurl-impersonate's own references to those symbols resolve inside
	 * it first, so they never bind to ext/curl's libcurl either. macOS gets
	 * the same isolation from two-level namespaces. */
	int flags = RTLD_NOW | RTLD_LOCAL;
#if defined(RTLD_DEEPBIND) && !defined(__SANITIZE_ADDRESS__)
	flags |= RTLD_DEEPBIND;
#endif

	return dlopen(path, flags);
}

zend_result php_curl_impersonate_api_load(const char *path, const char **error)
{
	static char message[1024];
	void *handle;

	if (path && *path) {
		handle = open_library(path);
	} else {
		path = PHP_CURL_IMPERSONATE_LIBRARY;
		handle = open_library(path);
		if (!handle) {
			/* Moved since the build: try the library search path */
			const char *first_error = dlerror();
			handle = open_library(PHP_CURL_IMPERSONATE_LIBRARY_NAME);
			if (handle) {
				path = PHP_CURL_IMPERSONATE_LIBRARY_NAME;
			} else {
				snprintf(message, sizeof(message), "Cannot load libcurl-impersonate: %s",
					first_error ? first_error : PHP_CURL_IMPERSONATE_LIBRARY);
				*error = message;
				return FAILURE;
			}
		}
	}

	if (!handle) {
		const char *dl_error = dlerror();

		snprintf(message, sizeof(message), "Cannot load libcurl-impersonate: %s", dl_error ? dl_error : path);
		*error = message;
		return FAILURE;
	}

#define PHP_CURL_IMPERSONATE_API_RESOLVE(name) \
	php_curl_impersonate_api.fn_ ## name = (__typeof__(php_curl_impersonate_api.fn_ ## name)) dlsym(handle, #name); \
	if (!php_curl_impersonate_api.fn_ ## name) { \
		snprintf(message, sizeof(message), "%s does not export " #name ", is it libcurl-impersonate?", path); \
		*error = message; \
		dlclose(handle); \
		return FAILURE; \
	}

	PHP_CURL_IMPERSONATE_FUNCTIONS(PHP_CURL_IMPERSONATE_API_RESOLVE)

#undef PHP_CURL_IMPERSONATE_API_RESOLVE

	/* Never dlclose()d: libcurl and the TLS library it bundles may still run
	 * thread-local and atexit() destructors after module shutdown. */
	loaded_library = path;

	return SUCCESS;
}

const char *php_curl_impersonate_api_library(void)
{
	return loaded_library;
}

#else /* PHP_CURL_IMPERSONATE_DLOPEN */

zend_result php_curl_impersonate_api_load(const char *path, const char **error)
{
	/* Linked at build time */
	(void) path;
	(void) error;

	return SUCCESS;
}

const char *php_curl_impersonate_api_library(void)
{
	return NULL;
}

#endif /* PHP_CURL_IMPERSONATE_DLOPEN */
