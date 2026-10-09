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
   | Author: Sterling Hughes <sterling@php.net>                           |
   |         Wez Furlong <wez@thebrainroom.com>                           |
   +----------------------------------------------------------------------+
*/

#ifndef _PHP_CURL_IMPERSONATE_H
#define _PHP_CURL_IMPERSONATE_H

#include "php.h"

#if PHP_VERSION_ID < 80400
# error "curl_impersonate requires PHP 8.4 or later"
#endif

#define PHP_CURL_IMPERSONATE_VERSION "0.1.0"

#ifdef PHP_WIN32
# ifdef PHP_CURL_IMPERSONATE_EXPORTS
#  define PHP_CURL_IMPERSONATE_API __declspec(dllexport)
# else
#  define PHP_CURL_IMPERSONATE_API __declspec(dllimport)
# endif
#elif defined(__GNUC__) && __GNUC__ >= 4
# define PHP_CURL_IMPERSONATE_API __attribute__ ((visibility("default")))
#else
# define PHP_CURL_IMPERSONATE_API
#endif

extern zend_module_entry curl_impersonate_module_entry;
#define phpext_curl_impersonate_ptr &curl_impersonate_module_entry

PHP_CURL_IMPERSONATE_API extern zend_class_entry *curl_impersonate_ce;
PHP_CURL_IMPERSONATE_API extern zend_class_entry *curl_impersonate_share_ce;
PHP_CURL_IMPERSONATE_API extern zend_class_entry *curl_impersonate_share_persistent_ce;
PHP_CURL_IMPERSONATE_API extern zend_class_entry *curl_impersonate_multi_ce;
PHP_CURL_IMPERSONATE_API extern zend_class_entry *curl_impersonate_CURLFile_class;
PHP_CURL_IMPERSONATE_API extern zend_class_entry *curl_impersonate_CURLStringFile_class;

#endif  /* _PHP_CURL_IMPERSONATE_H */
