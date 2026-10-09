--TEST--
curl_impersonate_impersonate()
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

function headers_sent_by(CurlImpersonateHandle $ch): string {
    curl_impersonate_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_impersonate_exec($ch);
    return curl_impersonate_getinfo($ch, CURLINFO_HEADER_OUT);
}

$ch = curl_impersonate_init("{$host}/get.inc?test=method");
var_dump(curl_impersonate_impersonate($ch, 'chrome136'));
$headers = headers_sent_by($ch);
var_dump(str_contains($headers, 'User-Agent: Mozilla/5.0'));
var_dump(str_contains($headers, 'sec-ch-ua: "Chromium";v="136"'));

echo "Without default headers:\n";
$ch = curl_impersonate_init("{$host}/get.inc?test=method");
var_dump(curl_impersonate_impersonate($ch, 'chrome136', false));
$headers = headers_sent_by($ch);
var_dump(str_contains($headers, 'User-Agent:'));

echo "Unknown target:\n";
$ch = curl_impersonate_init();
var_dump(curl_impersonate_impersonate($ch, 'netscape4'));
var_dump(curl_impersonate_errno($ch) === CURLE_BAD_FUNCTION_ARGUMENT);
var_dump(curl_impersonate_error($ch));

try {
    curl_impersonate_impersonate($ch, "chrome136\0");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECTF--
bool(true)
bool(true)
bool(true)
Without default headers:
bool(true)
bool(false)
Unknown target:
bool(false)
bool(true)
string(%d) "Unknown impersonation target 'netscape4'.%s"
curl_impersonate_impersonate(): Argument #2 ($target) must not contain any null bytes
