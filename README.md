# curl_impersonate for PHP

`curl_impersonate` is a PHP 8.4 extension that gives you PHP's familiar cURL API
on top of [curl-impersonate](https://github.com/lexiforest/curl-impersonate).
Requests can match the TLS and HTTP/2 fingerprints and default headers of real
browsers such as Chrome, Firefox, Safari and Edge.

The extension mirrors PHP's built-in `curl` extension (php-src's current `ext/curl`),
with every function renamed from `curl_*` to `curl_impersonate_*`. The two
extensions can be loaded together, and each uses its own libcurl.

```php
$ch = curl_impersonate_init('https://www.example.com');
curl_impersonate_impersonate($ch, 'chrome136');
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$html = curl_impersonate_exec($ch);
```

---

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
  - [1. Install curl-impersonate](#1-install-curl-impersonate)
  - [2. Build the extension](#2-build-the-extension)
  - [3. Enable the extension](#3-enable-the-extension)
  - [4. Check it works](#4-check-it-works)
- [Usage](#usage)
  - [Your first request](#your-first-request)
  - [Choosing a browser to impersonate](#choosing-a-browser-to-impersonate)
  - [Sending POST data and JSON](#sending-post-data-and-json)
  - [Uploading files](#uploading-files)
  - [Response information and errors](#response-information-and-errors)
  - [Running requests in parallel](#running-requests-in-parallel)
  - [Sharing cookies and connections between handles](#sharing-cookies-and-connections-between-handles)
  - [Fine-tuning the fingerprint](#fine-tuning-the-fingerprint)
  - [Using it alongside ext/curl](#using-it-alongside-extcurl)
- [Configuration (php.ini)](#configuration-phpini)
- [Reference](#reference)
- [Troubleshooting](#troubleshooting)
- [Running the tests](#running-the-tests)

---

## Requirements

- PHP 8.4+ with its development tools (`phpize` and `php-config`)
- A C compiler and `make`
- curl-impersonate (`libcurl-impersonate` and its headers)

By default the build expects curl-impersonate at its default install
location:

| | Linux | macOS |
|---|---|---|
| Library | `/usr/local/lib/libcurl-impersonate.so` | `/usr/local/lib/libcurl-impersonate.dylib` |
| Headers | `/usr/local/include/curl/` | `/usr/local/include/curl/` |

If yours is somewhere else, see the `--with-curl-impersonate=DIR` option
[below](#2-build-the-extension).

---

## Installation

### 1. Install curl-impersonate

Building curl-impersonate from source installs the library and headers to
`/usr/local`, which is where this extension looks by default.

**Linux (Debian/Ubuntu)**

```bash
sudo apt install -y git gcc g++ binutils patch make cmake ninja-build ca-certificates curl
```

```bash
git clone https://github.com/lexiforest/curl-impersonate.git && cd curl-impersonate
```

```bash
make prepare-libidn2 && make configure && make build
```

```bash
sudo make install && sudo ldconfig
```

**macOS**

```bash
brew install make cmake ninja
```

```bash
git clone https://github.com/lexiforest/curl-impersonate.git && cd curl-impersonate
```

```bash
gmake configure && gmake build && sudo gmake install
```

For other platforms and options, see curl-impersonate's
[build guide](https://github.com/lexiforest/curl-impersonate/blob/main/docs/building.rst).
Prebuilt packages also work, as long as they include both the library and the
`include/curl/*.h` headers.

You can confirm the install with:

```bash
curl-impersonate --version
```

The first line should mention `IMPERSONATE` and `BoringSSL`.

### 2. Build the extension

From this directory:

```bash
phpize
```

```bash
./configure --with-curl-impersonate
```

```bash
make
```

```bash
sudo make install
```

If curl-impersonate is installed under a different prefix, pass that prefix
(the directory that contains `include/` and `lib/`):

```bash
./configure --with-curl-impersonate=/opt/curl-impersonate
```

If you have several PHP versions installed, use the matching tools, e.g.
`phpize8.4` and `./configure --with-php-config=/path/to/php-config8.4 ...`.

### 3. Enable the extension

Find the directory PHP scans for extra ini files:

```bash
php-config --ini-dir
```

Create a `curl_impersonate.ini` file in the php ini directory:

```ini
echo "extension=curl_impersonate" | sudo tee $(php-config --ini-dir)/curl_impersonate.ini
```

Typical locations:

| Setup | Directory |
|---|---|
| Homebrew PHP on macOS | `/opt/homebrew/etc/php/8.4/conf.d/` |
| Debian/Ubuntu (ondrej/php) | `/etc/php/8.4/mods-available/`, then run `sudo phpenmod curl_impersonate` |
| Official Docker `php` images | `/usr/local/etc/php/conf.d/`, or run `docker-php-ext-enable curl_impersonate` |

Restart PHP-FPM, Apache or your web server so it picks up the change.

### 4. Check it works

```bash
php --ri curl_impersonate
```

You should see `curl-impersonate support => enabled` and a cURL version ending
in `-IMPERSONATE`. For a quick end-to-end test:

```bash
php -r '$ch = curl_impersonate_init("https://tls.peet.ws/api/clean"); curl_impersonate_impersonate($ch, "chrome136"); curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true); echo curl_impersonate_exec($ch), PHP_EOL;'
```

The response shows the TLS fingerprint the server saw. It should match Chrome
136, not a stock curl.

---

## Usage

If you have used PHP's cURL functions before, you already know this API: add
`impersonate_` after `curl_` and use the same options and constants.

| PHP's ext/curl | curl_impersonate |
|---|---|
| `curl_init()` | `curl_impersonate_init()` |
| `curl_setopt()` / `curl_setopt_array()` | `curl_impersonate_setopt()` / `curl_impersonate_setopt_array()` |
| `curl_exec()` | `curl_impersonate_exec()` |
| `curl_getinfo()`, `curl_error()`, `curl_errno()` | `curl_impersonate_getinfo()`, `curl_impersonate_error()`, `curl_impersonate_errno()` |
| `curl_multi_*()`, `curl_share_*()` | `curl_impersonate_multi_*()`, `curl_impersonate_share_*()` |

### Your first request

```php
<?php
$ch = curl_impersonate_init('https://www.example.com');

// Look like Chrome 136: TLS fingerprint, HTTP/2 settings and default headers
curl_impersonate_impersonate($ch, 'chrome136');

curl_impersonate_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT        => 30,
]);

$html = curl_impersonate_exec($ch);

if ($html === false) {
    echo 'Request failed: ', curl_impersonate_error($ch), PHP_EOL;
} else {
    echo 'HTTP ', curl_impersonate_getinfo($ch, CURLINFO_RESPONSE_CODE), PHP_EOL;
    echo strlen($html), ' bytes received', PHP_EOL;
}
```

Handles are freed automatically when they go out of scope, as with ext/curl,
so no close call is needed.

**Compressed responses are decoded for you.** Browsers ask for compressed
responses (`Accept-Encoding: gzip, deflate, br, zstd`), and impersonation
sends that header. libcurl only decompresses a body when
`CURLOPT_ACCEPT_ENCODING` is set, so impersonating a browser sets it to `''`
for you. This doesn't change any headers: the browser's own `Accept-Encoding`
is what goes out.

- A `CURLOPT_ACCEPT_ENCODING` you set yourself always takes priority, whether
  you set it before or after choosing a browser. To receive the raw compressed
  bytes, set it to `null`.
- While the browser's headers are in use, the option's text is never sent. To
  advertise different encodings, override the header itself:
  `CURLOPT_HTTPHEADER => ['Accept-Encoding: gzip']`. This makes the request
  look less like the real browser.
- With `default_headers` turned off (see below), no browser headers are sent
  and the option is left alone, as in plain libcurl. Set
  `CURLOPT_ACCEPT_ENCODING` yourself if you want compressed responses.

### Choosing a browser to impersonate

Targets are browser names plus versions, for example:

| Browser | Example targets |
|---|---|
| Chrome | `chrome99` … `chrome136`, `chrome142`, `chrome145`, `chrome146`, `chrome150`, `chrome131_android` |
| Edge | `edge99`, `edge101` |
| Firefox | `firefox133`, `firefox135`, `firefox144`, `firefox147` |
| Safari | `safari170`, `safari180`, `safari184`, `safari260`, `safari184_ios`, `safari260_ios` |
| Tor | `tor145` |

The exact list depends on your curl-impersonate version. Each
`curl_<target>` wrapper script it installed (e.g. `/usr/local/bin/curl_chrome136`)
is a valid target. There are three ways to choose one.

**1. Per handle, with a function call**

```php
$ch = curl_impersonate_init('https://www.example.com');

if (!curl_impersonate_impersonate($ch, 'firefox147')) {
    // Unknown target
    throw new RuntimeException(curl_impersonate_error($ch));
}
```

**2. Per handle, as an option.** This is handy with `curl_impersonate_setopt_array()`.

```php
curl_impersonate_setopt_array($ch, [
    CURLOPT_IMPERSONATE    => 'safari184',
    CURLOPT_RETURNTRANSFER => true,
]);
```

**3. For every handle, with an INI setting.** Every new handle, and every
handle reset with `curl_impersonate_reset()`, then impersonates that browser:

```ini
; php.ini
curl_impersonate.target = chrome136
```

```php
// ...or at runtime
ini_set('curl_impersonate.target', 'chrome136');

$ch = curl_impersonate_init('https://www.example.com'); // already impersonating Chrome 136
```

If the target in `curl_impersonate.target` is unknown,
`curl_impersonate_init()` returns `false` with a warning, so a request never
goes out without impersonation by mistake.

**Keeping your own headers**

By default, impersonation also adds the browser's usual request headers
(`User-Agent`, `Accept`, `sec-ch-ua`, …). To get the browser's TLS and HTTP/2
fingerprint but send only your own headers, turn those defaults off:

```php
curl_impersonate_impersonate($ch, 'chrome136', default_headers: false);

// or
curl_impersonate_setopt($ch, CURLOPT_IMPERSONATE, 'chrome136:no');

// or, for the INI setting
ini_set('curl_impersonate.default_headers', '0');
```

Headers you set with `CURLOPT_HTTPHEADER` or `CURLOPT_USERAGENT` override the
browser defaults with the same name, and the browser's header order is kept:

```php
curl_impersonate_impersonate($ch, 'chrome136');
curl_impersonate_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept-Language: de-DE,de;q=0.9',
    'Referer: https://www.example.com/',
]);
```

### Sending POST data and JSON

```php
// URL-encoded form
$ch = curl_impersonate_init('https://httpbin.org/post');
curl_impersonate_impersonate($ch, 'chrome136');
curl_impersonate_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => http_build_query(['q' => 'php', 'page' => 2]),
]);
$response = curl_impersonate_exec($ch);
```

```php
// JSON body
$ch = curl_impersonate_init('https://httpbin.org/post');
curl_impersonate_impersonate($ch, 'chrome136');
curl_impersonate_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => json_encode(['name' => 'Ada', 'role' => 'admin']),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
]);
$data = json_decode(curl_impersonate_exec($ch), true);
```

```php
// Other methods
curl_impersonate_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
```

### Uploading files

Passing an array to `CURLOPT_POSTFIELDS` sends `multipart/form-data`. Files
are attached with `CURLImpersonateFile` (from disk) or
`CURLImpersonateStringFile` (from memory):

```php
$ch = curl_impersonate_init('https://httpbin.org/post');
curl_impersonate_impersonate($ch, 'chrome136');
curl_impersonate_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS     => [
        'title'  => 'Quarterly report',
        'report' => new CURLImpersonateFile('/path/to/report.pdf', 'application/pdf', 'report.pdf'),
        'notes'  => new CURLImpersonateStringFile("line 1\nline 2", 'notes.txt', 'text/plain'),
    ],
]);
$response = curl_impersonate_exec($ch);
```

`curl_impersonate_file_create($path, $mime, $name)` is a shortcut for
`new CURLImpersonateFile(...)`. If PHP's built-in curl extension is loaded,
its `CURLFile` and `CURLStringFile` objects are accepted too.

### Response information and errors

```php
$ch = curl_impersonate_init('https://httpbin.org/redirect/2');
curl_impersonate_impersonate($ch, 'chrome136');
curl_impersonate_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
]);

$body = curl_impersonate_exec($ch);

if ($body === false) {
    printf("Error %d: %s\n", curl_impersonate_errno($ch), curl_impersonate_error($ch));
    exit(1);
}

// A single value...
echo curl_impersonate_getinfo($ch, CURLINFO_RESPONSE_CODE), PHP_EOL;  // 200
echo curl_impersonate_getinfo($ch, CURLINFO_EFFECTIVE_URL), PHP_EOL;  // final URL
echo curl_impersonate_getinfo($ch, CURLINFO_HTTP_VERSION), PHP_EOL;   // 3 = CURL_HTTP_VERSION_2_0

// ...or everything at once
print_r(curl_impersonate_getinfo($ch));

// curl-impersonate extra: every redirect that was followed ("<status>\t<url>")
print_r(curl_impersonate_getinfo($ch, CURLINFO_REDIRECT_HISTORY));
```

To see the exact request headers that were sent, which is useful for checking
impersonation:

```php
curl_impersonate_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_impersonate_exec($ch);
echo curl_impersonate_getinfo($ch, CURLINFO_HEADER_OUT);
```

To receive response headers, use a header callback:

```php
$headers = [];
curl_impersonate_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, string $line) use (&$headers) {
    if (str_contains($line, ':')) {
        [$name, $value] = explode(':', $line, 2);
        $headers[strtolower(trim($name))][] = trim($value);
    }
    return strlen($line);
});
```

### Running requests in parallel

```php
$urls = [
    'https://www.example.com/',
    'https://www.example.org/',
    'https://www.example.net/',
];

$mh = curl_impersonate_multi_init();
$handles = [];

foreach ($urls as $url) {
    $ch = curl_impersonate_init($url);
    curl_impersonate_impersonate($ch, 'chrome136');
    curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_impersonate_multi_add_handle($mh, $ch);
    $handles[$url] = $ch;
}

do {
    $status = curl_impersonate_multi_exec($mh, $running);
    if ($running) {
        curl_impersonate_multi_select($mh);
    }
} while ($running && $status === CURLM_OK);

foreach ($handles as $url => $ch) {
    $code = curl_impersonate_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $size = strlen(curl_impersonate_multi_getcontent($ch));
    echo "$url -> HTTP $code, $size bytes\n";
    curl_impersonate_multi_remove_handle($mh, $ch);
}
```

### Sharing cookies and connections between handles

A share handle lets several handles share cookies, DNS lookups and TLS
sessions within one PHP request:

```php
$sh = curl_impersonate_share_init();
curl_impersonate_share_setopt($sh, CURLSHOPT_SHARE, CURL_LOCK_DATA_COOKIE);
curl_impersonate_share_setopt($sh, CURLSHOPT_SHARE, CURL_LOCK_DATA_DNS);
curl_impersonate_share_setopt($sh, CURLSHOPT_SHARE, CURL_LOCK_DATA_SSL_SESSION);

$login = curl_impersonate_init('https://www.example.com/login');
curl_impersonate_setopt($login, CURLOPT_SHARE, $sh);
curl_impersonate_setopt($login, CURLOPT_COOKIEFILE, ''); // enable the cookie engine
// ... log in ...

$page = curl_impersonate_init('https://www.example.com/account');
curl_impersonate_setopt($page, CURLOPT_SHARE, $sh);     // sends the login cookies
```

To keep cookies on disk between scripts, use `CURLOPT_COOKIEJAR` and
`CURLOPT_COOKIEFILE` with a file path. To keep connections and DNS between PHP
requests (e.g. under PHP-FPM), use a persistent share handle:

```php
$sh = curl_impersonate_share_init_persistent([CURL_LOCK_DATA_DNS, CURL_LOCK_DATA_CONNECT]);
curl_impersonate_setopt($ch, CURLOPT_SHARE, $sh);
```

### Fine-tuning the fingerprint

A target sets many low-level options at once. You can also set them
yourself, on top of a target or instead of one:

```php
curl_impersonate_impersonate($ch, 'chrome136');

curl_impersonate_setopt_array($ch, [
    // TLS ClientHello
    CURLOPT_TLS_GREASE            => true,
    CURLOPT_SSL_CERT_COMPRESSION  => 'brotli',
    CURLOPT_SSL_ENABLE_ALPS       => true,

    // HTTP/2 SETTINGS frame and pseudo-header order (m=method, a=authority, s=scheme, p=path)
    CURLOPT_HTTP2_SETTINGS              => '1:65536;2:0;4:6291456;6:262144',
    CURLOPT_HTTP2_PSEUDO_HEADERS_ORDER  => 'masp',

    // Headers sent before your own CURLOPT_HTTPHEADER values
    CURLOPT_HTTPBASEHEADER => ['User-Agent: MyBrowser/1.0'],
]);
```

All of curl-impersonate's extra options are available as `CURLOPT_*`
constants (`CURLOPT_TLS_EXTENSION_ORDER`, `CURLOPT_SSL_SIG_HASH_ALGS`,
`CURLOPT_HTTP3_*`, `CURLOPT_WS_*`, …). See
[curl-impersonate's documentation](https://github.com/lexiforest/curl-impersonate)
for what each one does. curl-impersonate numbers its options from 999 to 1999 (plus libcurl's type
offset), so you can list the ones your build supports with:

```php
print_r(array_filter(
    get_defined_constants(),
    fn ($value, $name) => str_starts_with($name, 'CURLOPT_')
        && $value % 10000 >= 999 && $value % 10000 < 2000,
    ARRAY_FILTER_USE_BOTH,
));
```

### Using it alongside ext/curl

Both extensions can be enabled together. Each keeps its own handles and its
own libcurl, so existing `curl_*` code is unaffected:

```php
// Plain libcurl (ext/curl)
$api = curl_init('https://api.internal.example/status');

// Browser-impersonating request (curl_impersonate)
$site = curl_impersonate_init('https://www.example.com');
curl_impersonate_impersonate($site, 'chrome136');
```

Handles can't be mixed. `curl_impersonate_exec(curl_init())` throws a
`TypeError`, and so does `curl_exec()` with an impersonate handle.

Switching existing code over is mostly a find and replace:

| Replace | With |
|---|---|
| `curl_` (function calls) | `curl_impersonate_` |
| `CurlHandle`, `CurlMultiHandle`, `CurlShareHandle` | `CurlImpersonateHandle`, `CurlImpersonateMultiHandle`, `CurlImpersonateShareHandle` |
| `CURLFile`, `CURLStringFile` | `CURLImpersonateFile`, `CURLImpersonateStringFile` (optional, the originals are accepted) |
| `curl.cainfo` | `curl_impersonate.cainfo` |

Constants such as `CURLOPT_URL` and `CURLE_OPERATION_TIMEDOUT` don't change.

Two behaviour differences:

- curl-impersonate never sends libcurl's default `Accept: */*` header. Set
  `Accept` yourself, or let impersonation add the browser's.
- While a browser is impersonated with its default headers, compressed
  responses are decoded automatically (`CURLOPT_ACCEPT_ENCODING` defaults to
  `''`). Without impersonation the default is unset, as in ext/curl.

---

## Configuration (php.ini)

| Setting | Default | Changeable | Description |
|---|---|---|---|
| `curl_impersonate.target` | *(empty)* | anywhere (`ini_set()`) | Browser applied to every new or reset handle, e.g. `chrome136`. Empty means no automatic impersonation. |
| `curl_impersonate.default_headers` | `On` | anywhere (`ini_set()`) | Whether `curl_impersonate.target` also adds the browser's default headers. |
| `curl_impersonate.cainfo` | *(empty)* | php.ini only | CA bundle used to verify HTTPS certificates. `openssl.cafile` takes priority if set. |

These settings take the place of curl-impersonate's `CURL_IMPERSONATE` and
`CURL_IMPERSONATE_HEADERS` environment variables. Those still work, but the INI
settings can be changed per script.

---

## Reference

**Functions.** Every function in PHP's cURL extension, with `curl_impersonate_`
in place of `curl_`. They take the same arguments and return the same values:

`curl_impersonate_init`, `curl_impersonate_setopt`, `curl_impersonate_setopt_array`,
`curl_impersonate_exec`, `curl_impersonate_getinfo`, `curl_impersonate_error`,
`curl_impersonate_errno`, `curl_impersonate_strerror`, `curl_impersonate_reset`,
`curl_impersonate_copy_handle`, `curl_impersonate_escape`, `curl_impersonate_unescape`,
`curl_impersonate_pause`, `curl_impersonate_upkeep`, `curl_impersonate_version`,
`curl_impersonate_file_create`, `curl_impersonate_close` (deprecated no-op),
`curl_impersonate_multi_*` (init, add_handle, remove_handle, exec, select,
getcontent, info_read, get_handles, setopt, errno, strerror, close),
`curl_impersonate_share_*` (init, init_persistent, setopt, errno, strerror,
close – deprecated no-op).

There is one addition:

```php
curl_impersonate_impersonate(CurlImpersonateHandle $handle, string $target, bool $default_headers = true): bool
```

**Classes.** `CurlImpersonateHandle`, `CurlImpersonateMultiHandle`,
`CurlImpersonateShareHandle`, `CurlImpersonateSharePersistentHandle`,
`CURLImpersonateFile`, `CURLImpersonateStringFile`.

**Constants.** These keep their usual names (`CURLOPT_*`, `CURLINFO_*`, `CURLE_*`,
`CURLM_*`, `CURLFOLLOW_*`, …), plus curl-impersonate's additions such as
`CURLOPT_IMPERSONATE`, `CURLINFO_REDIRECT_HISTORY`, `CURLINFO_COOKIECHANGES` and
`CURLFOLLOW_SAFE`.

If PHP's own curl extension is also loaded, it already defines the shared
constants, and their values are almost always identical. A few masks
(`CURLAUTH_ANY`, `CURLAUTH_ANYSAFE`, `CURLPROTO_ALL`, `CURLSSH_AUTH_ANY`, …)
differ between libcurl versions. In that case the original name keeps
ext/curl's value, and curl-impersonate's value is also available with `CURL`
replaced by `CURLIMP` (e.g. `CURLIMPAUTH_ANY`). Both values work in practice.

---

## Troubleshooting

**`configure: error: Cannot find the curl-impersonate headers`**
curl-impersonate isn't installed in `/usr/local` (or `/usr`). Pass its prefix,
for example `./configure --with-curl-impersonate=/opt/curl-impersonate`.

**`Unable to load dynamic library 'curl_impersonate'` / `libcurl-impersonate.so.4: cannot open shared object file`**
On Linux, run `sudo ldconfig` after installing curl-impersonate. If it lives
outside the linker path, add its `lib` directory to `/etc/ld.so.conf.d/`.

**`curl_impersonate was built against libcurl ...-IMPERSONATE, but its libcurl calls resolve to libcurl ...`**
Another libcurl in the same process is taking over curl-impersonate's
functions. This can happen on musl-based systems such as Alpine, where the
extension can't be isolated from PHP's own curl extension. Disable
`ext/curl` in that PHP setup, or use a glibc-based image (e.g. Debian).

**`SSL certificate problem: unable to get local issuer certificate`**
Point the extension at your system's CA bundle in php.ini:

```ini
curl_impersonate.cainfo = /etc/ssl/certs/ca-certificates.crt   ; Debian/Ubuntu
; curl_impersonate.cainfo = /etc/pki/tls/certs/ca-bundle.crt   ; RHEL/Fedora
; curl_impersonate.cainfo = /opt/homebrew/etc/ca-certificates/cert.pem  ; Homebrew
```

**The response body is unreadable binary data.**
The server sent a compressed response that libcurl didn't decode. Decoding is
switched on automatically only while a browser's default headers are in use.
If you impersonate with `default_headers: false`, without a browser, or have
set `CURLOPT_ACCEPT_ENCODING` to `null`, set `CURLOPT_ACCEPT_ENCODING` to `''`
(see [Your first request](#your-first-request)).

**A target is rejected.** The list of browsers depends on your curl-impersonate
version. Check which `curl_<target>` scripts it installed:

```bash
ls /usr/local/bin/curl_*
```

---

## Running the tests

```bash
make test
```

The test suite is php-src's cURL test suite converted to this extension, plus
`tests/impersonate_*.phpt` for the curl-impersonate features. Tests that need a
local [Caddy](https://caddyserver.com) server or internet access are skipped
when those aren't available.
