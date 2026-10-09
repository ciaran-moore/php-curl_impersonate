--TEST--
curl_impersonate_error() function - basic test for curl_impersonate_error using a fake url
--CREDITS--
Mattijs Hoitink mattijshoitink@gmail.com
#Testfest Utrecht 2009
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if(getenv("SKIP_ONLINE_TESTS")) die("skip online test");
if(getenv("SKIP_SLOW_TESTS")) die("skip slow test");
$url = "fakeURL";
$ip = gethostbyname($url);
if ($ip != $url) die("skip 'fakeURL' resolves to $ip\n");

?>
--FILE--
<?php
/*
 * Description:   Returns a clear text error message for the last cURL operation.
 * Source:        ext/curl/interface.c
 * Documentation: http://wiki.php.net/qa/temp/ext/curl
 */

// Fake URL to trigger an error
$url = "fakeURL";

echo "== Testing curl_impersonate_error with a fake URL ==\n";

// cURL handler
$ch = curl_impersonate_init($url);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_impersonate_exec($ch);
var_dump(curl_impersonate_error($ch));
?>
--EXPECTF--
== Testing curl_impersonate_error with a fake URL ==
string(%d) "%sfakeURL%S"
