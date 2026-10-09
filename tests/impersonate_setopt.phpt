--TEST--
curl-impersonate specific options via curl_impersonate_setopt()
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

/* The User-Agent request header that was sent, or "(none)" */
function useragent(CurlImpersonateHandle $ch): string {
    curl_impersonate_setopt($ch, CURLOPT_URL, $GLOBALS['host'] . '/get.inc?test=method');
    curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_impersonate_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_impersonate_exec($ch);
    $sent = curl_impersonate_getinfo($ch, CURLINFO_HEADER_OUT);
    return preg_match('/^User-Agent: (.*)\r$/mi', $sent, $m) ? $m[1] : '(none)';
}

echo "CURLOPT_IMPERSONATE:\n";
$ch = curl_impersonate_init("{$host}/get.inc?test=useragent");
var_dump(curl_impersonate_setopt($ch, CURLOPT_IMPERSONATE, 'chrome136'));
var_dump(str_contains(useragent($ch), 'Chrome/136'));

$ch = curl_impersonate_init("{$host}/get.inc?test=useragent");
var_dump(curl_impersonate_setopt($ch, CURLOPT_IMPERSONATE, 'chrome136:no'));
var_dump(useragent($ch));

$ch = curl_impersonate_init();
var_dump(curl_impersonate_setopt($ch, CURLOPT_IMPERSONATE, 'netscape4'));
var_dump(curl_impersonate_errno($ch) === CURLE_BAD_FUNCTION_ARGUMENT);

echo "Long, string and list options:\n";
$ch = curl_impersonate_init("{$host}/get.inc?test=useragent");
var_dump(curl_impersonate_setopt_array($ch, [
    CURLOPT_TLS_GREASE => true,
    CURLOPT_SSL_ENABLE_ALPS => 1,
    CURLOPT_HTTP2_SETTINGS => '1:65536;2:0;4:6291456;6:262144',
    CURLOPT_SSL_CERT_COMPRESSION => 'brotli',
    CURLOPT_HTTPBASEHEADER => ['User-Agent: base-header-agent'],
]));
var_dump(useragent($ch));

$ch = curl_impersonate_init("{$host}/get.inc?test=useragent");
curl_impersonate_setopt($ch, CURLOPT_HTTPBASEHEADER, ['User-Agent: base-header-agent']);
curl_impersonate_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: overridden']);
var_dump(useragent($ch));

echo "Copies keep the options:\n";
$ch = curl_impersonate_init("{$host}/get.inc?test=useragent");
curl_impersonate_setopt($ch, CURLOPT_HTTPBASEHEADER, ['User-Agent: copied-agent']);
$copy = curl_impersonate_copy_handle($ch);
unset($ch);
var_dump(useragent($copy));

echo "Errors:\n";
$ch = curl_impersonate_init();
try {
    curl_impersonate_setopt($ch, CURLOPT_HTTPBASEHEADER, 'User-Agent: x');
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}
try {
    curl_impersonate_setopt($ch, CURLOPT_HTTP2_SETTINGS, "1:65536\0");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
try {
    curl_impersonate_setopt($ch, CURLOPT_HTTPBASEHEADER, ["X-Test: a\0b"]);
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(curl_impersonate_setopt($ch, CURLOPT_IMPERSONATE, null));
?>
--EXPECT--
CURLOPT_IMPERSONATE:
bool(true)
bool(true)
bool(true)
string(6) "(none)"
bool(false)
bool(true)
Long, string and list options:
bool(true)
string(17) "base-header-agent"
string(10) "overridden"
Copies keep the options:
string(12) "copied-agent"
Errors:
curl_impersonate_setopt(): The CURLOPT_HTTPBASEHEADER option must have an array value
curl_impersonate_setopt(): cURL option CURLOPT_HTTP2_SETTINGS must not contain any null bytes
curl_impersonate_setopt(): cURL option CURLOPT_HTTPBASEHEADER must not contain any null bytes
bool(false)
