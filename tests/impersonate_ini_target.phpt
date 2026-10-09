--TEST--
curl_impersonate.target and curl_impersonate.default_headers INI settings
--EXTENSIONS--
curl_impersonate
--INI--
curl_impersonate.target=chrome136
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

$ch = curl_impersonate_init();
var_dump(str_contains(useragent($ch), 'Chrome/136'));

echo "Reset re-applies the target:\n";
curl_impersonate_setopt($ch, CURLOPT_USERAGENT, 'custom');
var_dump(useragent($ch));
curl_impersonate_reset($ch);
var_dump(str_contains(useragent($ch), 'Chrome/136'));

echo "Without default headers:\n";
ini_set('curl_impersonate.default_headers', '0');
var_dump(useragent(curl_impersonate_init()));
ini_set('curl_impersonate.default_headers', '1');

echo "Disabled:\n";
ini_set('curl_impersonate.target', '');
var_dump(useragent(curl_impersonate_init()));

echo "Unknown target:\n";
ini_set('curl_impersonate.target', 'netscape4');
var_dump(curl_impersonate_init());
?>
--EXPECTF--
bool(true)
Reset re-applies the target:
string(6) "custom"
bool(true)
Without default headers:
string(6) "(none)"
Disabled:
string(6) "(none)"
Unknown target:

Warning: curl_impersonate_init(): Could not impersonate "netscape4" (curl_impersonate.target): A libcurl function was given a bad argument in %s on line %d
bool(false)
