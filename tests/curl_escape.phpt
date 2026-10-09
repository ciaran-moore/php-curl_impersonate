--TEST--
Test curl_impersonate_escape and curl_impersonate_unescape() functions
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$str = "http://www.php.net/ ?!";

$a = curl_impersonate_init();
$escaped = curl_impersonate_escape($a, $str);
$original = curl_impersonate_unescape($a, $escaped);
var_dump($escaped, $original);
var_dump(curl_impersonate_unescape($a, 'a%00b'));
?>
--EXPECTF--
string(36) "http%3A%2F%2Fwww.php.net%2F%20%3F%21"
string(22) "http://www.php.net/ ?!"
string(3) "a%0b"
