PHP_ARG_WITH([curl-impersonate],
  [for curl-impersonate support],
  [AS_HELP_STRING([[--with-curl-impersonate[=DIR]]],
    [Include curl-impersonate support. DIR is the curl-impersonate
    installation prefix [/usr/local]])])

if test "$PHP_CURL_IMPERSONATE" != "no"; then
  AC_MSG_CHECKING([for curl-impersonate installation])
  CURL_IMPERSONATE_DIR=
  if test "$PHP_CURL_IMPERSONATE" = "yes"; then
    for i in /usr/local /usr; do
      if test -r "$i/include/curl/easy.h" && grep -q curl_easy_impersonate "$i/include/curl/easy.h"; then
        CURL_IMPERSONATE_DIR=$i
        break
      fi
    done
  elif test -r "$PHP_CURL_IMPERSONATE/include/curl/easy.h"; then
    CURL_IMPERSONATE_DIR=$PHP_CURL_IMPERSONATE
  fi

  AS_VAR_IF([CURL_IMPERSONATE_DIR], [],
    [AC_MSG_RESULT([not found])
    AC_MSG_ERROR([Cannot find the curl-impersonate headers (curl/easy.h declaring curl_easy_impersonate). Install curl-impersonate or pass --with-curl-impersonate=DIR])])
  AC_MSG_RESULT([$CURL_IMPERSONATE_DIR])

  CURL_IMPERSONATE_LIBDIR=
  for i in $PHP_LIBDIR lib64 lib; do
    if test -n "$i" && ls "$CURL_IMPERSONATE_DIR/$i"/libcurl-impersonate.* >/dev/null 2>&1; then
      CURL_IMPERSONATE_LIBDIR=$CURL_IMPERSONATE_DIR/$i
      break
    fi
  done
  AS_VAR_IF([CURL_IMPERSONATE_LIBDIR], [],
    [AC_MSG_ERROR([Cannot find libcurl-impersonate in $CURL_IMPERSONATE_DIR/lib])])

  dnl The curl-impersonate headers must win over any stock libcurl headers
  dnl (e.g. /usr/include/curl from a libcurl -dev package).
  PHP_ADD_INCLUDE([$CURL_IMPERSONATE_DIR/include], [1])

  PHP_CHECK_LIBRARY([curl-impersonate],
    [curl_easy_impersonate],
    [AC_DEFINE([HAVE_CURL_IMPERSONATE], [1],
      [Define to 1 if the PHP extension 'curl_impersonate' is available.])],
    [AC_MSG_FAILURE([The libcurl-impersonate check failed.])],
    [-L$CURL_IMPERSONATE_LIBDIR])

  dnl Adds -L and the runtime search path (rpath); the macOS dylib has an
  dnl @rpath install name, so the rpath is required there.
  PHP_ADD_LIBRARY_WITH_PATH([curl-impersonate],
    [$CURL_IMPERSONATE_LIBDIR],
    [CURL_IMPERSONATE_SHARED_LIBADD])

  dnl libtool links macOS bundles with -flat_namespace, which makes every
  dnl curl_* call resolve to whichever libcurl the process loaded first
  dnl (ext/curl's). A two-level namespace binds them to libcurl-impersonate,
  dnl while PHP's own symbols are still looked up dynamically.
  AS_CASE([$host_os], [darwin*], [
    CURL_IMPERSONATE_SHARED_LIBADD="$CURL_IMPERSONATE_SHARED_LIBADD -Wl,-twolevel_namespace -Wl,-undefined,dynamic_lookup"
  ])

  dnl curl-impersonate specific options are enum values, so they cannot be
  dnl detected with #ifdef. Check each one so the extension builds against
  dnl older and newer curl-impersonate releases.
  save_CPPFLAGS=$CPPFLAGS
  CPPFLAGS="$CPPFLAGS -I$CURL_IMPERSONATE_DIR/include"
  AC_CHECK_DECLS([
    CURLOPT_IMPERSONATE,
    CURLOPT_HTTPBASEHEADER,
    CURLOPT_SSL_SIG_HASH_ALGS,
    CURLOPT_SSL_ENABLE_ALPS,
    CURLOPT_SSL_CERT_COMPRESSION,
    CURLOPT_SSL_ENABLE_TICKET,
    CURLOPT_HTTP2_PSEUDO_HEADERS_ORDER,
    CURLOPT_HTTP2_SETTINGS,
    CURLOPT_SSL_PERMUTE_EXTENSIONS,
    CURLOPT_HTTP2_WINDOW_UPDATE,
    CURLOPT_HTTP2_STREAMS,
    CURLOPT_TLS_GREASE,
    CURLOPT_TLS_EXTENSION_ORDER,
    CURLOPT_STREAM_EXCLUSIVE,
    CURLOPT_TLS_KEY_USAGE_NO_CHECK,
    CURLOPT_TLS_SIGNED_CERT_TIMESTAMPS,
    CURLOPT_TLS_STATUS_REQUEST,
    CURLOPT_TLS_DELEGATED_CREDENTIALS,
    CURLOPT_TLS_RECORD_SIZE_LIMIT,
    CURLOPT_TLS_KEY_SHARES_LIMIT,
    CURLOPT_TLS_USE_NEW_ALPS_CODEPOINT,
    CURLOPT_HTTP2_NO_PRIORITY,
    CURLOPT_PROXY_CREDENTIAL_NO_REUSE,
    CURLOPT_SPLIT_COOKIES,
    CURLOPT_FORM_BOUNDARY,
    CURLOPT_HTTP3_PSEUDO_HEADERS_ORDER,
    CURLOPT_HTTP3_SETTINGS,
    CURLOPT_QUIC_TRANSPORT_PARAMETERS,
    CURLOPT_HTTP3_SIG_HASH_ALGS,
    CURLOPT_HTTP3_TLS_EXTENSION_ORDER,
    CURLOPT_HTTPHEADER_ORDER,
    CURLOPT_HTTP3_HTTPHEADER,
    CURLOPT_HTTP3_HTTPHEADER_ORDER,
    CURLOPT_HTTP3_SSL_EC_CURVES,
    CURLOPT_WS_HTTPHEADER,
    CURLOPT_WS_HTTPHEADER_ORDER,
    CURLOPT_WS_SSL_DISABLE_TICKET,
    CURLOPT_WS_SSL_CERT_COMPRESSION,
    CURLOPT_QUIC_CID_LENGTH,
    CURLOPT_HTTP3_SSL_PERMUTE_EXTENSIONS,
    CURLOPT_TLS_TRUST_ANCHORS,
    CURLOPT_QUIC_INITIAL_PACKET_NUMBER,
    CURLINFO_COOKIECHANGES,
    CURLINFO_REDIRECT_HISTORY], [], [], [[#include <curl/curl.h>]])
  CPPFLAGS=$save_CPPFLAGS

  dnl -fvisibility=hidden keeps every internal symbol private, so this
  dnl extension can never bind to (or be bound by) ext/curl's symbols.
  PHP_NEW_EXTENSION([curl_impersonate],
    [interface.c multi.c share.c curl_file.c impersonate.c],
    [$ext_shared],,
    [-fvisibility=hidden])
  PHP_INSTALL_HEADERS([ext/curl_impersonate], [php_curl_impersonate.h])
  PHP_SUBST([CURL_IMPERSONATE_SHARED_LIBADD])
fi
