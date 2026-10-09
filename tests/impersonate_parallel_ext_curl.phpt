--TEST--
curl_impersonate runs side by side with ext/curl
--EXTENSIONS--
curl
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$imp = curl_impersonate_version();
$std = curl_version();
var_dump(str_contains($imp['version'], 'IMPERSONATE'));
var_dump(str_contains($std['version'], 'IMPERSONATE'));

// Constants shared with ext/curl keep their names and values
var_dump(CURLOPT_URL === 10002, CURLE_URL_MALFORMAT === 3, CURLINFO_EFFECTIVE_URL === 0x100001);
var_dump(defined('CURLOPT_IMPERSONATE'));

// Interleave transfers on both extensions in one process
$a = curl_init("{$host}/get.inc?test=useragent");
$b = curl_impersonate_init("{$host}/get.inc?test=useragent");
curl_setopt($a, CURLOPT_RETURNTRANSFER, true);
curl_setopt($a, CURLOPT_USERAGENT, 'ext-curl');
curl_impersonate_setopt($b, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_impersonate($b, 'chrome136');
var_dump(curl_exec($a));
var_dump(str_contains(curl_impersonate_exec($b), 'Chrome/136'));
var_dump(curl_exec($a));

// Handles are not interchangeable
try {
    curl_impersonate_exec($a);
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}
try {
    curl_exec($b);
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)
string(8) "ext-curl"
bool(true)
string(8) "ext-curl"
curl_impersonate_exec(): Argument #1 ($handle) must be of type CurlImpersonateHandle, CurlHandle given
curl_exec(): Argument #1 ($handle) must be of type CurlHandle, CurlImpersonateHandle given
