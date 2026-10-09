--TEST--
Test curl_impersonate_error() & curl_impersonate_errno() function with problematic protocol
--CREDITS--
TestFest 2009 - AFUP - Perrick Penet <perrick@noparking.net>
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

// Make sure the scheme always starts with an alphabetic character.
$url = 'a' . substr(uniqid(),0,6)."://www.example.com";
$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, $url);

curl_impersonate_exec($ch);
var_dump(curl_impersonate_error($ch));
var_dump(curl_impersonate_errno($ch));
?>
--EXPECTF--
string(%d) "%Srotocol%s"
int(1)
