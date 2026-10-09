#!/usr/bin/env php
<?php

/**
 * Generates stubs/curl_impersonate.php, a PHP stub file that IDEs and static
 * analysers (PhpStorm, Intelephense, PHPStan, Psalm) use to know the
 * extension's functions, classes and constants.
 *
 * Signatures, default values and constant values are read from the built
 * extension with Reflection, so run this with the extension loaded:
 *
 *   php -d extension=./modules/curl_impersonate.so gen-ide-stubs.php
 *
 * Options:
 *   --all-constants  Also include the constants shared with ext/curl
 *                    (CURLOPT_URL, CURLE_OK, ...). Leave this off when the
 *                    IDE already ships ext/curl's stubs, which is the default
 *                    in PhpStorm, Intelephense, PHPStan and Psalm, otherwise
 *                    every shared constant is declared twice.
 *   --output=FILE    Where to write the stubs. "-" writes to stdout.
 *                    Defaults to stubs/curl_impersonate.php.
 */

const EXTENSION = 'curl_impersonate';

const STUB_FILES = [
    __DIR__ . '/curl_impersonate.stub.php',
    __DIR__ . '/curl_impersonate_file.stub.php',
];

const DEFAULT_OUTPUT = __DIR__ . '/stubs/curl_impersonate.php';

const PHP_MANUAL = 'https://www.php.net/manual/en/';

const CURL_IMPERSONATE_DOCS = 'https://github.com/lexiforest/curl-impersonate';

/**
 * Per-function documentation. "manual" is the ext/curl function this one
 * mirrors, used for the @link to the PHP manual. Parameter and return types
 * come from Reflection, unless "param_types" / "return_type" give a more
 * precise PHPDoc type.
 */
const FUNCTION_DOCS = [
    'curl_impersonate_close' => [
        'manual' => 'curl_close',
        'summary' => 'Does nothing. Handles are freed automatically once nothing references them.',
        'params' => ['handle' => 'A handle returned by curl_impersonate_init().'],
    ],
    'curl_impersonate_copy_handle' => [
        'manual' => 'curl_copy_handle',
        'summary' => 'Copies a handle together with all of its options, including the impersonated browser.',
        'params' => ['handle' => 'The handle to copy.'],
        'return' => 'A new handle, or false on failure.',
    ],
    'curl_impersonate_errno' => [
        'manual' => 'curl_errno',
        'summary' => 'Returns the error number of the last operation on the handle.',
        'params' => ['handle' => 'A handle returned by curl_impersonate_init().'],
        'return' => 'A CURLE_* error code, or 0 if no error occurred.',
    ],
    'curl_impersonate_error' => [
        'manual' => 'curl_error',
        'summary' => 'Returns a description of the last error on the handle.',
        'params' => ['handle' => 'A handle returned by curl_impersonate_init().'],
        'return' => 'The error message, or an empty string if no error occurred.',
    ],
    'curl_impersonate_escape' => [
        'manual' => 'curl_escape',
        'summary' => 'URL-encodes a string as described in RFC 3986.',
        'params' => [
            'handle' => 'A handle returned by curl_impersonate_init().',
            'string' => 'The string to encode.',
        ],
        'return' => 'The encoded string, or false on failure.',
    ],
    'curl_impersonate_unescape' => [
        'manual' => 'curl_unescape',
        'summary' => 'Decodes a URL-encoded string.',
        'params' => [
            'handle' => 'A handle returned by curl_impersonate_init().',
            'string' => 'The URL-encoded string to decode.',
        ],
        'return' => 'The decoded string, or false on failure.',
    ],
    'curl_impersonate_multi_setopt' => [
        'manual' => 'curl_multi_setopt',
        'summary' => 'Sets an option on a multi handle.',
        'params' => [
            'multi_handle' => 'A handle returned by curl_impersonate_multi_init().',
            'option' => 'One of the CURLMOPT_* constants.',
            'value' => 'The value for the option.',
        ],
        'return' => 'true on success, false on failure.',
    ],
    'curl_impersonate_exec' => [
        'manual' => 'curl_exec',
        'summary' => 'Performs the transfer configured on the handle.',
        'params' => ['handle' => 'A handle returned by curl_impersonate_init().'],
        'return' => 'false on failure. On success, the response body when CURLOPT_RETURNTRANSFER is set, true otherwise.',
    ],
    'curl_impersonate_file_create' => [
        'manual' => 'curl_file_create',
        'summary' => 'Creates a CURLImpersonateFile for uploading a file with CURLOPT_POSTFIELDS. Same as new CURLImpersonateFile().',
        'params' => [
            'filename' => 'Path to the file to upload.',
            'mime_type' => 'MIME type of the file. Defaults to application/octet-stream.',
            'posted_filename' => 'File name sent in the upload. Defaults to the base name of $filename.',
        ],
    ],
    'curl_impersonate_getinfo' => [
        'manual' => 'curl_getinfo',
        'summary' => 'Returns information about the last transfer on the handle.',
        'params' => [
            'handle' => 'A handle returned by curl_impersonate_init().',
            'option' => 'One of the CURLINFO_* constants, including curl-impersonate\'s CURLINFO_COOKIECHANGES and CURLINFO_REDIRECT_HISTORY. If null, all available information is returned as an associative array.',
        ],
        'return' => 'The requested value, an associative array when $option is null, or false on failure.',
    ],
    'curl_impersonate_init' => [
        'manual' => 'curl_init',
        'summary' => 'Creates a new handle. If the curl_impersonate.target INI setting is set, the handle already impersonates that browser.',
        'params' => ['url' => 'If given, CURLOPT_URL is set to this value.'],
        'return' => 'A new handle, or false on failure, including when curl_impersonate.target names an unknown browser.',
    ],
    'curl_impersonate_upkeep' => [
        'manual' => 'curl_upkeep',
        'summary' => 'Performs connection upkeep checks, such as an HTTP/2 PING, on the handle\'s open connection.',
        'params' => ['handle' => 'A handle returned by curl_impersonate_init().'],
        'return' => 'true on success, false on failure.',
    ],
    'curl_impersonate_multi_add_handle' => [
        'manual' => 'curl_multi_add_handle',
        'summary' => 'Adds a handle to a multi handle.',
        'params' => [
            'multi_handle' => 'A handle returned by curl_impersonate_multi_init().',
            'handle' => 'A handle returned by curl_impersonate_init().',
        ],
        'return' => 'CURLM_OK (0) on success, or one of the other CURLM_* error codes.',
    ],
    'curl_impersonate_multi_get_handles' => [
        'manual' => 'curl_multi_get_handles',
        'summary' => 'Returns the handles currently added to a multi handle.',
        'params' => ['multi_handle' => 'A handle returned by curl_impersonate_multi_init().'],
        'return' => 'The handles, in no particular order.',
        'return_type' => 'list<CurlImpersonateHandle>',
    ],
    'curl_impersonate_multi_close' => [
        'manual' => 'curl_multi_close',
        'summary' => 'Removes every handle from a multi handle.',
        'params' => ['multi_handle' => 'A handle returned by curl_impersonate_multi_init().'],
    ],
    'curl_impersonate_multi_errno' => [
        'manual' => 'curl_multi_errno',
        'summary' => 'Returns the error number of the last operation on the multi handle.',
        'params' => ['multi_handle' => 'A handle returned by curl_impersonate_multi_init().'],
        'return' => 'A CURLM_* error code, or 0 if no error occurred.',
    ],
    'curl_impersonate_multi_exec' => [
        'manual' => 'curl_multi_exec',
        'summary' => 'Runs every transfer in the multi handle that can make progress, without blocking.',
        'params' => [
            'multi_handle' => 'A handle returned by curl_impersonate_multi_init().',
            'still_running' => 'Set to the number of transfers that are still running.',
        ],
        'param_types' => ['still_running' => 'int'],
        'return' => 'CURLM_OK (0), or one of the other CURLM_* error codes. Errors of individual transfers are reported by curl_impersonate_multi_info_read().',
    ],
    'curl_impersonate_multi_getcontent' => [
        'manual' => 'curl_multi_getcontent',
        'summary' => 'Returns the response body of a handle that has CURLOPT_RETURNTRANSFER set.',
        'params' => ['handle' => 'A handle that was run through a multi handle.'],
        'return' => 'The response body, or null if CURLOPT_RETURNTRANSFER is not set.',
    ],
    'curl_impersonate_multi_info_read' => [
        'manual' => 'curl_multi_info_read',
        'summary' => 'Returns the next message about the transfers in a multi handle, such as a finished transfer.',
        'params' => [
            'multi_handle' => 'A handle returned by curl_impersonate_multi_init().',
            'queued_messages' => 'Set to the number of messages still waiting after this one.',
        ],
        'param_types' => ['queued_messages' => 'int'],
        'return' => 'An array with the keys "msg" (CURLMSG_DONE), "result" (a CURLE_* code) and "handle", or false when there are no more messages.',
        'return_type' => 'array{msg: int, result: int, handle: CurlImpersonateHandle}|false',
    ],
    'curl_impersonate_multi_init' => [
        'manual' => 'curl_multi_init',
        'summary' => 'Creates a multi handle, which runs several transfers in parallel.',
    ],
    'curl_impersonate_multi_remove_handle' => [
        'manual' => 'curl_multi_remove_handle',
        'summary' => 'Removes a handle from a multi handle.',
        'params' => [
            'multi_handle' => 'A handle returned by curl_impersonate_multi_init().',
            'handle' => 'A handle that was added with curl_impersonate_multi_add_handle().',
        ],
        'return' => 'CURLM_OK (0) on success, or one of the other CURLM_* error codes.',
    ],
    'curl_impersonate_multi_select' => [
        'manual' => 'curl_multi_select',
        'summary' => 'Waits until a transfer in the multi handle has activity, or until the timeout expires.',
        'params' => [
            'multi_handle' => 'A handle returned by curl_impersonate_multi_init().',
            'timeout' => 'Maximum time to wait, in seconds.',
        ],
        'return' => 'The number of active file descriptors, which may be 0, or -1 on failure.',
    ],
    'curl_impersonate_multi_strerror' => [
        'manual' => 'curl_multi_strerror',
        'summary' => 'Returns the description of a multi error code.',
        'params' => ['error_code' => 'One of the CURLM_* constants.'],
        'return' => 'The description, or null for an unknown error code.',
    ],
    'curl_impersonate_pause' => [
        'manual' => 'curl_pause',
        'summary' => 'Pauses or resumes a transfer.',
        'params' => [
            'handle' => 'A handle returned by curl_impersonate_init().',
            'flags' => 'One of the CURLPAUSE_* constants.',
        ],
        'return' => 'A CURLE_* error code, 0 on success.',
    ],
    'curl_impersonate_reset' => [
        'manual' => 'curl_reset',
        'summary' => 'Resets every option on the handle to its default. If the curl_impersonate.target INI setting is set, that browser is impersonated again.',
        'params' => ['handle' => 'A handle returned by curl_impersonate_init().'],
    ],
    'curl_impersonate_setopt_array' => [
        'manual' => 'curl_setopt_array',
        'summary' => 'Sets several options on a handle. Stops at the first option that cannot be set.',
        'params' => [
            'handle' => 'A handle returned by curl_impersonate_init().',
            'options' => 'Option values keyed by CURLOPT_* constant.',
        ],
        'param_types' => ['options' => 'array<int, mixed>'],
        'return' => 'true if every option was set, false otherwise.',
    ],
    'curl_impersonate_setopt' => [
        'manual' => 'curl_setopt',
        'summary' => 'Sets an option on a handle.',
        'params' => [
            'handle' => 'A handle returned by curl_impersonate_init().',
            'option' => 'One of the CURLOPT_* constants, including curl-impersonate\'s options such as CURLOPT_IMPERSONATE.',
            'value' => 'The value for the option.',
        ],
        'return' => 'true on success, false on failure.',
    ],
    'curl_impersonate_share_close' => [
        'manual' => 'curl_share_close',
        'summary' => 'Does nothing. Share handles are freed automatically once nothing references them.',
        'params' => ['share_handle' => 'A handle returned by curl_impersonate_share_init().'],
    ],
    'curl_impersonate_share_errno' => [
        'manual' => 'curl_share_errno',
        'summary' => 'Returns the error number of the last operation on the share handle.',
        'params' => ['share_handle' => 'A handle returned by curl_impersonate_share_init().'],
        'return' => 'A CURLSHE_* error code, or 0 if no error occurred.',
    ],
    'curl_impersonate_share_init' => [
        'manual' => 'curl_share_init',
        'summary' => 'Creates a share handle, which lets several handles share data such as cookies, the DNS cache and connections.',
    ],
    'curl_impersonate_share_setopt' => [
        'manual' => 'curl_share_setopt',
        'summary' => 'Sets an option on a share handle.',
        'params' => [
            'share_handle' => 'A handle returned by curl_impersonate_share_init().',
            'option' => 'CURLSHOPT_SHARE or CURLSHOPT_UNSHARE.',
            'value' => 'One of the CURL_LOCK_DATA_* constants.',
        ],
        'return' => 'true on success, false on failure.',
    ],
    'curl_impersonate_share_strerror' => [
        'manual' => 'curl_share_strerror',
        'summary' => 'Returns the description of a share error code.',
        'params' => ['error_code' => 'One of the CURLSHE_* constants.'],
        'return' => 'The description, or null for an unknown error code.',
    ],
    'curl_impersonate_share_init_persistent' => [
        'manual' => 'curl_share_init_persistent',
        'summary' => 'Returns a share handle that lives for the whole PHP process, across requests. Calls with the same options get the same underlying share.',
        'params' => [
            'share_options' => 'CURL_LOCK_DATA_DNS, CURL_LOCK_DATA_CONNECT and/or CURL_LOCK_DATA_SSL_SESSION. Cookies cannot be shared across requests.',
        ],
        'param_types' => ['share_options' => 'list<int>'],
        'throws' => ['ValueError' => 'If $share_options is empty or contains an unsupported value.'],
    ],
    'curl_impersonate_strerror' => [
        'manual' => 'curl_strerror',
        'summary' => 'Returns the description of an error code.',
        'params' => ['error_code' => 'One of the CURLE_* constants.'],
        'return' => 'The description, or null for an unknown error code.',
    ],
    'curl_impersonate_version' => [
        'manual' => 'curl_version',
        'summary' => 'Returns information about the libcurl-impersonate library the extension uses.',
        'return' => 'An associative array with keys such as "version", "version_number", "ssl_version", "host", "features", "feature_list" and "protocols", or false on failure.',
        'return_type' => 'array<string, mixed>|false',
    ],
    'curl_impersonate_impersonate' => [
        'summary' => 'Makes the handle impersonate a browser: its TLS and HTTP/2 fingerprints and, optionally, its default request headers.',
        'params' => [
            'handle' => 'A handle returned by curl_impersonate_init().',
            'target' => 'The browser to impersonate, e.g. "chrome136", "firefox147" or "safari184". The targets available depend on the curl-impersonate version.',
            'default_headers' => 'Whether to also send the browser\'s default headers (User-Agent, Accept, sec-ch-ua, ...). Headers set with CURLOPT_HTTPHEADER override them.',
        ],
        'return' => 'true on success, false if the target is unknown. curl_impersonate_errno() and curl_impersonate_error() then describe the error.',
        'see' => [CURL_IMPERSONATE_DOCS],
    ],
];

const CLASS_DOCS = [
    'CurlImpersonateHandle' => [
        'summary' => 'A handle for a single transfer. Cannot be constructed directly, use curl_impersonate_init().',
        'manual' => 'class.curlhandle',
    ],
    'CurlImpersonateMultiHandle' => [
        'summary' => 'A handle that runs several transfers in parallel. Cannot be constructed directly, use curl_impersonate_multi_init().',
        'manual' => 'class.curlmultihandle',
    ],
    'CurlImpersonateShareHandle' => [
        'summary' => 'A handle that shares data between transfers. Cannot be constructed directly, use curl_impersonate_share_init().',
        'manual' => 'class.curlsharehandle',
    ],
    'CurlImpersonateSharePersistentHandle' => [
        'summary' => 'A share handle that lives across requests. Cannot be constructed directly, use curl_impersonate_share_init_persistent().',
        'manual' => 'class.curlsharepersistenthandle',
        'properties' => ['options' => ['The CURL_LOCK_DATA_* options the handle shares.', 'list<int>']],
    ],
    'CURLImpersonateFile' => [
        'summary' => 'A file to upload with CURLOPT_POSTFIELDS.',
        'manual' => 'class.curlfile',
        'properties' => [
            'name' => ['Path to the file to upload.'],
            'mime' => ['MIME type of the file.'],
            'postname' => ['File name sent in the upload.'],
        ],
        'methods' => [
            '__construct' => [
                'summary' => 'Creates a file to upload.',
                'params' => [
                    'filename' => 'Path to the file to upload.',
                    'mime_type' => 'MIME type of the file. Defaults to application/octet-stream.',
                    'posted_filename' => 'File name sent in the upload. Defaults to the base name of $filename.',
                ],
            ],
            'getFilename' => ['summary' => 'Returns the path of the file to upload.'],
            'getMimeType' => ['summary' => 'Returns the MIME type of the file.'],
            'getPostFilename' => ['summary' => 'Returns the file name sent in the upload.'],
            'setMimeType' => [
                'summary' => 'Sets the MIME type of the file.',
                'params' => ['mime_type' => 'The MIME type.'],
            ],
            'setPostFilename' => [
                'summary' => 'Sets the file name sent in the upload.',
                'params' => ['posted_filename' => 'The file name.'],
            ],
        ],
    ],
    'CURLImpersonateStringFile' => [
        'summary' => 'A string to upload as a file with CURLOPT_POSTFIELDS.',
        'manual' => 'class.curlstringfile',
        'properties' => [
            'data' => ['The contents of the file.'],
            'postname' => ['File name sent in the upload.'],
            'mime' => ['MIME type of the file.'],
        ],
        'methods' => [
            '__construct' => [
                'summary' => 'Creates a file to upload from a string.',
                'params' => [
                    'data' => 'The contents of the file.',
                    'postname' => 'File name sent in the upload.',
                    'mime' => 'MIME type of the file.',
                ],
            ],
        ],
    ],
];

/** Descriptions of curl-impersonate's own constants, from its curl/curl.h. */
const CONSTANT_DOCS = [
    'CURLOPT_IMPERSONATE' => 'Browser to impersonate, as "target" or "target:no" to leave out the browser\'s default headers. Value: string.',
    'CURLOPT_HTTPBASEHEADER' => 'Headers sent by the impersonated browser, merged with CURLOPT_HTTPHEADER. Value: array of strings.',
    'CURLOPT_SSL_SIG_HASH_ALGS' => 'TLS signature hash algorithms (RFC 5246, section 7.4.1.4.1). Value: string.',
    'CURLOPT_SSL_ENABLE_ALPS' => 'Whether to send the ALPS extension in the TLS ClientHello. Value: bool.',
    'CURLOPT_SSL_CERT_COMPRESSION' => 'Comma-separated certificate compression algorithms to advertise (RFC 8879): "zlib", "brotli". Value: string.',
    'CURLOPT_SSL_ENABLE_TICKET' => 'Whether to send the TLS session ticket extension (RFC 5077). Value: bool.',
    'CURLOPT_HTTP2_PSEUDO_HEADERS_ORDER' => 'Order of the HTTP/2 pseudo-headers, using m (:method), a (:authority), s (:scheme) and p (:path), e.g. "masp". Value: string.',
    'CURLOPT_HTTP2_SETTINGS' => 'HTTP/2 SETTINGS frame keys and values, e.g. "1:65536;2:0;4:6291456". Value: string.',
    'CURLOPT_SSL_PERMUTE_EXTENSIONS' => 'Whether BoringSSL shuffles the order of TLS extensions. Value: bool.',
    'CURLOPT_HTTP2_WINDOW_UPDATE' => 'Initial HTTP/2 WINDOW_UPDATE increment. Value: int.',
    'CURLOPT_HTTP2_STREAMS' => 'Initial HTTP/2 stream settings. Value: string.',
    'CURLOPT_TLS_GREASE' => 'Whether to send GREASE values in the TLS ClientHello. Value: bool.',
    'CURLOPT_TLS_EXTENSION_ORDER' => 'Order of the TLS extensions in the ClientHello. Value: string.',
    'CURLOPT_STREAM_EXCLUSIVE' => 'HTTP/2 stream exclusive flag, 0 or 1. Value: int.',
    'CURLOPT_TLS_KEY_USAGE_NO_CHECK' => 'Whether to skip the TLS certificate key usage check. Value: bool.',
    'CURLOPT_TLS_SIGNED_CERT_TIMESTAMPS' => 'Whether to send the TLS signed certificate timestamps extension. Value: bool.',
    'CURLOPT_TLS_STATUS_REQUEST' => 'Whether to send the TLS status request (OCSP stapling) extension. Value: bool.',
    'CURLOPT_TLS_DELEGATED_CREDENTIALS' => 'TLS delegated credentials signature algorithms, as sent by Firefox. Value: string.',
    'CURLOPT_TLS_RECORD_SIZE_LIMIT' => 'TLS record size limit, as sent by Firefox. Value: int.',
    'CURLOPT_TLS_KEY_SHARES_LIMIT' => 'Maximum number of TLS key shares, as sent by Firefox. Value: int.',
    'CURLOPT_TLS_USE_NEW_ALPS_CODEPOINT' => 'Whether to use the new ALPS extension code point. Value: bool.',
    'CURLOPT_HTTP2_NO_PRIORITY' => 'Whether to leave out the priority flag in HTTP/2 HEADERS frames. Value: bool.',
    'CURLOPT_PROXY_CREDENTIAL_NO_REUSE' => 'Whether to stop reusing TLS sessions and connections made with different proxy credentials. Value: bool.',
    'CURLOPT_SPLIT_COOKIES' => 'Whether to send each cookie in its own Cookie header. Value: bool.',
    'CURLOPT_FORM_BOUNDARY' => 'Style of the multipart/form-data boundary. Value: string.',
    'CURLOPT_HTTP3_PSEUDO_HEADERS_ORDER' => 'Order of the HTTP/3 pseudo-headers, using m (:method), a (:authority), s (:scheme) and p (:path). Value: string.',
    'CURLOPT_HTTP3_SETTINGS' => 'HTTP/3 SETTINGS frame keys and values, e.g. "1:65536;6:262144". Value: string.',
    'CURLOPT_QUIC_TRANSPORT_PARAMETERS' => 'QUIC transport parameters, as "id:value;id:value". Value: string.',
    'CURLOPT_HTTP3_SIG_HASH_ALGS' => 'Signature hash algorithms for HTTP/3 (QUIC TLS), used there instead of CURLOPT_SSL_SIGNATURE_ALGORITHMS. Value: string.',
    'CURLOPT_HTTP3_TLS_EXTENSION_ORDER' => 'TLS extension order for HTTP/3 (QUIC TLS), used there instead of CURLOPT_TLS_EXTENSION_ORDER. Value: string.',
    'CURLOPT_HTTPHEADER_ORDER' => 'Comma-separated order of the regular HTTP headers. Value: string.',
    'CURLOPT_HTTP3_HTTPHEADER' => 'Headers for HTTP/3 connections, used there instead of CURLOPT_HTTPHEADER. Value: array of strings.',
    'CURLOPT_HTTP3_HTTPHEADER_ORDER' => 'Order of the regular HTTP headers on HTTP/3 connections. Value: string.',
    'CURLOPT_HTTP3_SSL_EC_CURVES' => 'Elliptic curves for HTTP/3 connections. Value: string.',
    'CURLOPT_WS_HTTPHEADER' => 'Headers for ws:// and wss:// requests, used there instead of CURLOPT_HTTPHEADER. Value: array of strings.',
    'CURLOPT_WS_HTTPHEADER_ORDER' => 'Order of the regular HTTP headers on WebSocket requests. Value: string.',
    'CURLOPT_WS_SSL_DISABLE_TICKET' => 'Whether to leave out the TLS session ticket extension on WebSocket connections. Value: bool.',
    'CURLOPT_WS_SSL_CERT_COMPRESSION' => 'Certificate compression algorithms for WebSocket connections. Value: string.',
    'CURLOPT_QUIC_CID_LENGTH' => 'QUIC initial connection ID length profile. Value: string.',
    'CURLOPT_HTTP3_SSL_PERMUTE_EXTENSIONS' => 'TLS extension shuffling for HTTP/3: one of the CURL_HTTP3_SSL_PERMUTE_* constants. Value: int.',
    'CURLOPT_TLS_TRUST_ANCHORS' => 'Comma-separated TLS trust anchor relative OIDs. Value: string.',
    'CURLOPT_QUIC_INITIAL_PACKET_NUMBER' => 'QUIC initial packet number. -1 uses Firefox\'s randomized distribution, other values are used as is. Value: int.',
    'CURLINFO_COOKIECHANGES' => 'Cookie changes accepted from the server in the last transfer, as "SET" or "DELETE" followed by Netscape cookie fields. Returns an array of strings.',
    'CURLINFO_REDIRECT_HISTORY' => 'Redirect responses followed in the last transfer, as the status code, a tab, and the request URL. Returns an array of strings.',
    'CURLFOLLOW_SAFE' => 'Value for CURLOPT_FOLLOWLOCATION: follow redirects, but not to internal or private IP addresses (SSRF protection).',
    'CURL_HTTP3_SSL_PERMUTE_FALLBACK' => 'Value for CURLOPT_HTTP3_SSL_PERMUTE_EXTENSIONS: use the CURLOPT_SSL_PERMUTE_EXTENSIONS setting.',
    'CURL_HTTP3_SSL_PERMUTE_DISABLE' => 'Value for CURLOPT_HTTP3_SSL_PERMUTE_EXTENSIONS: keep the TLS extension order.',
    'CURL_HTTP3_SSL_PERMUTE_ENABLE' => 'Value for CURLOPT_HTTP3_SSL_PERMUTE_EXTENSIONS: shuffle the TLS extension order.',
];

/** Guards in curl_impersonate.stub.php that mark curl-impersonate's own constants. */
const IMPERSONATE_GUARD_REGEX = '/^#if(?:def)?\s+(?:HAVE_DECL_\w+|(?:defined\()?(?:CURLFOLLOW_SAFE|CURL_HTTP3_SSL_PERMUTE_\w+))/';

/** Stands in for the spaces of a PHPDoc type while its tag is word-wrapped. */
const NO_BREAK = "\0";

main($argv);

function main(array $argv): void
{
    $allConstants = false;
    $output = DEFAULT_OUTPUT;

    foreach (array_slice($argv, 1) as $arg) {
        if ($arg === '--all-constants') {
            $allConstants = true;
        } elseif (str_starts_with($arg, '--output=')) {
            $output = substr($arg, strlen('--output='));
        } else {
            fwrite(STDERR, "Unknown argument: $arg\n");
            exit(1);
        }
    }

    if (!extension_loaded(EXTENSION)) {
        fwrite(STDERR, "The " . EXTENSION . " extension is not loaded. Run:\n"
            . "  php -d extension=./modules/" . EXTENSION . ".so " . basename(__FILE__) . "\n");
        exit(1);
    }

    $ext = new ReflectionExtension(EXTENSION);
    $code = renderHeader($ext, $allConstants)
        . renderConstants($allConstants)
        . renderClasses($ext)
        . renderFunctions($ext);

    if ($output === '-') {
        echo $code;
        return;
    }
    if (!is_dir(dirname($output))) {
        mkdir(dirname($output), 0777, true);
    }
    file_put_contents($output, $code);
    fwrite(STDERR, "Wrote $output\n");
}

function renderHeader(ReflectionExtension $ext, bool $allConstants): string
{
    $libcurl = curl_impersonate_version()['version'] ?? 'unknown';
    $link = CURL_IMPERSONATE_DOCS;
    $constants = $allConstants
        ? 'Constants: every constant the extension defines, including those shared with ext/curl.'
        : "Constants: only curl-impersonate's own additions. The constants shared with\n"
            . " * ext/curl (CURLOPT_URL, CURLE_OK, ...) come from your IDE's ext/curl stubs.";

    return <<<PHP
    <?php

    /**
     * IDE stubs for the curl_impersonate PHP extension {$ext->getVersion()}.
     *
     * This file is only for IDEs and static analysers. Never include it at
     * runtime. Generated by gen-ide-stubs.php against libcurl {$libcurl};
     * regenerate it instead of editing it.
     *
     * {$constants}
     *
     * INI settings: curl_impersonate.target, curl_impersonate.default_headers,
     * curl_impersonate.cainfo.
     *
     * @link {$link}
     */

    // @codingStandardsIgnoreFile
    // phpcs:ignoreFile


    PHP;
}

/**
 * Reads the constant declarations from the gen_stub files, in order, with the
 * preprocessor guard each one is under.
 *
 * @return list<array{name: string, guard: ?string, deprecated: bool}>
 */
function readStubConstants(): array
{
    $constants = [];

    foreach (STUB_FILES as $file) {
        $guard = null;
        $doc = '';
        foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
            if (str_starts_with($line, '#if')) {
                $guard = $line;
            } elseif (str_starts_with($line, '#endif')) {
                $guard = null;
            } elseif (str_starts_with(ltrim($line), '/**')) {
                $doc = $line;
            } elseif ($doc !== '' && !str_starts_with(ltrim($line), 'const ')) {
                $doc .= "\n" . $line;
            }

            if (preg_match('/^const\s+(\w+)\s*=/', $line, $m)) {
                $constants[] = [
                    'name' => $m[1],
                    'guard' => $guard,
                    'deprecated' => str_contains($doc, '@deprecated'),
                ];
                $doc = '';
            }
        }
    }

    return $constants;
}

function renderConstants(bool $allConstants): string
{
    $out = '';
    $missing = [];

    foreach (readStubConstants() as $constant) {
        $name = $constant['name'];
        $own = $constant['guard'] !== null && preg_match(IMPERSONATE_GUARD_REGEX, $constant['guard']);

        if (!$allConstants && !$own) {
            continue;
        }
        if (!defined($name)) {
            // Not available in the curl-impersonate / libcurl version the
            // extension was built against.
            $missing[] = $name;
            continue;
        }

        $tags = [];
        if ($constant['deprecated']) {
            $tags[] = '@deprecated';
        }
        if ($own) {
            $tags[] = '@link ' . CURL_IMPERSONATE_DOCS;
        }

        $out .= renderDocComment(CONSTANT_DOCS[$name] ?? null, $tags, '');
        $out .= 'const ' . $name . ' = ' . exportValue(@constant($name)) . ";\n\n";
    }

    if ($missing) {
        fwrite(STDERR, "Skipped constants not defined by this build: " . implode(', ', $missing) . "\n");
    }

    return $out;
}

function renderClasses(ReflectionExtension $ext): string
{
    $out = '';

    foreach ($ext->getClasses() as $class) {
        $docs = CLASS_DOCS[$class->getName()] ?? [];
        $tags = [];
        if (isset($docs['manual'])) {
            $tags[] = '@link ' . PHP_MANUAL . $docs['manual'] . '.php';
        }
        $out .= renderDocComment($docs['summary'] ?? null, $tags, '');

        $modifiers = '';
        if ($class->isFinal()) {
            $modifiers .= 'final ';
        }
        if ($class->isAbstract() && !$class->isInterface()) {
            $modifiers .= 'abstract ';
        }
        $out .= $modifiers . 'class ' . $class->getName();
        if ($parent = $class->getParentClass()) {
            $out .= ' extends ' . $parent->getName();
        }
        if ($interfaces = $class->getInterfaceNames()) {
            $out .= ' implements ' . implode(', ', $interfaces);
        }
        $out .= "\n{\n";

        $members = [];
        foreach ($class->getProperties() as $property) {
            if ($property->getDeclaringClass()->getName() !== $class->getName()) {
                continue;
            }
            $members[] = renderProperty($property, $docs['properties'][$property->getName()] ?? []);
        }
        foreach ($class->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() !== $class->getName()) {
                continue;
            }
            $members[] = renderFunction($method, $docs['methods'][$method->getName()] ?? [], '    ');
        }

        $out .= implode("\n", $members) . "}\n\n";
    }

    return $out;
}

function renderProperty(ReflectionProperty $property, array $docs): string
{
    $tags = [];
    $type = $property->getType();
    if (isset($docs[1])) {
        $tags[] = '@var ' . unbreakable($docs[1]);
    }

    $code = renderDocComment($docs[0] ?? null, $tags, '    ') . '    ';
    $code .= $property->isPublic() ? 'public ' : ($property->isProtected() ? 'protected ' : 'private ');
    if ($property->isStatic()) {
        $code .= 'static ';
    }
    if ($property->isReadOnly()) {
        $code .= 'readonly ';
    }
    if ($type !== null) {
        $code .= $type . ' ';
    }
    $code .= '$' . $property->getName();
    if ($property->hasDefaultValue()) {
        $code .= ' = ' . exportValue($property->getDefaultValue());
    }

    return $code . ";\n";
}

function renderFunctions(ReflectionExtension $ext): string
{
    $out = '';

    foreach ($ext->getFunctions() as $function) {
        $out .= renderFunction($function, FUNCTION_DOCS[$function->getName()] ?? [], '') . "\n";
    }

    return rtrim($out) . "\n";
}

function renderFunction(ReflectionFunctionAbstract $function, array $docs, string $indent): string
{
    $tags = [];

    if (isset($docs['manual'])) {
        $tags[] = '@link ' . PHP_MANUAL . 'function.' . str_replace('_', '-', $docs['manual']) . '.php';
    }
    foreach ($docs['see'] ?? [] as $see) {
        $tags[] = '@link ' . $see;
    }

    $params = [];
    foreach ($function->getParameters() as $param) {
        $name = $param->getName();
        $docType = $docs['param_types'][$name] ?? ($param->getType() !== null ? docType($param->getType()) : 'mixed');
        $tags[] = rtrim('@param ' . unbreakable($docType) . ' ' . ($param->isVariadic() ? '...' : '') . '$' . $name
            . ' ' . ($docs['params'][$name] ?? ''));
        $params[] = renderParameter($param);
    }

    $returnType = $function->hasReturnType() ? $function->getReturnType() : null;
    if ($returnType === null && $function instanceof ReflectionMethod && $function->hasTentativeReturnType()) {
        $returnType = $function->getTentativeReturnType();
    }
    if ($returnType !== null && (string) $returnType !== 'void' && ($function->getName() !== '__construct')) {
        $tags[] = rtrim('@return ' . unbreakable($docs['return_type'] ?? docType($returnType)) . ' ' . ($docs['return'] ?? ''));
    }

    foreach ($docs['throws'] ?? [] as $class => $description) {
        $tags[] = '@throws ' . $class . ' ' . $description;
    }

    $attributes = '';
    foreach ($function->getAttributes() as $attribute) {
        $args = [];
        foreach ($attribute->getArguments() as $key => $value) {
            $args[] = (is_string($key) ? $key . ': ' : '') . exportValue($value);
        }
        $attributes .= $indent . '#[\\' . $attribute->getName() . ($args ? '(' . implode(', ', $args) . ')' : '') . "]\n";

        if ($attribute->getName() === 'Deprecated') {
            $arguments = $attribute->getArguments();
            $tags[] = rtrim('@deprecated ' . ($arguments['since'] ?? '') . ' ' . ucfirst(preg_replace('/^as /', '', $arguments['message'] ?? '')));
        }
    }

    $code = renderDocComment($docs['summary'] ?? null, $tags, $indent) . $attributes . $indent;
    if ($function instanceof ReflectionMethod) {
        if ($function->isFinal()) {
            $code .= 'final ';
        }
        if ($function->isAbstract()) {
            $code .= 'abstract ';
        }
        $code .= $function->isPublic() ? 'public ' : ($function->isProtected() ? 'protected ' : 'private ');
        if ($function->isStatic()) {
            $code .= 'static ';
        }
    }
    $code .= 'function ' . ($function->returnsReference() ? '&' : '') . $function->getName()
        . '(' . implode(', ', $params) . ')';
    if ($returnType !== null) {
        $code .= ': ' . $returnType;
    }

    return $code . " {}\n";
}

function renderParameter(ReflectionParameter $param): string
{
    $code = '';
    if ($param->getType() !== null) {
        $code .= $param->getType() . ' ';
    }
    if ($param->isPassedByReference()) {
        $code .= '&';
    }
    if ($param->isVariadic()) {
        $code .= '...';
    }
    $code .= '$' . $param->getName();

    if ($param->isDefaultValueAvailable()) {
        $code .= ' = ' . ($param->isDefaultValueConstant()
            ? $param->getDefaultValueConstantName()
            : exportValue($param->getDefaultValue()));
    }

    return $code;
}

/** Converts a Reflection type to its PHPDoc form, e.g. "?string" to "string|null". */
function docType(ReflectionType $type): string
{
    if ($type instanceof ReflectionNamedType && $type->allowsNull() && $type->getName() !== 'mixed' && $type->getName() !== 'null') {
        return $type->getName() . '|null';
    }

    return (string) $type;
}

function renderDocComment(?string $summary, array $tags, string $indent): string
{
    $lines = [];
    if ($summary !== null) {
        foreach (explode("\n", wordwrap($summary, 76 - strlen($indent))) as $line) {
            $lines[] = $line;
        }
    }
    if ($summary !== null && $tags) {
        $lines[] = '';
    }
    foreach ($tags as $tag) {
        // Continuation lines of a tag are indented under its description
        foreach (explode("\n", wordwrap($tag, 76 - strlen($indent))) as $i => $line) {
            $lines[] = ($i > 0 ? '    ' : '') . str_replace(NO_BREAK, ' ', $line);
        }
    }
    if (!$lines) {
        return '';
    }

    $doc = $indent . "/**\n";
    foreach ($lines as $line) {
        $doc .= rtrim($indent . ' * ' . $line) . "\n";
    }

    return $doc . $indent . " */\n";
}

/** Keeps a PHPDoc type such as "array{a: int, b: int}" on one line when wrapping. */
function unbreakable(string $type): string
{
    return str_replace(' ', NO_BREAK, $type);
}

function exportValue(mixed $value): string
{
    return match (true) {
        $value === null => 'null',
        is_bool($value) => $value ? 'true' : 'false',
        is_array($value) && $value === [] => '[]',
        default => var_export($value, true),
    };
}
