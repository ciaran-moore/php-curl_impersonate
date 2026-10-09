--TEST--
Test curl_impersonate_error() & curl_impersonate_errno() function without url
--CREDITS--
TestFest 2009 - AFUP - Perrick Penet <perrick@noparking.net>
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

//In January 2008 , level 7.18.0 of the curl lib, many of the messages changed.
//The final crlf was removed. This test is coded to work with or without the crlf.

$ch = curl_impersonate_init();

curl_impersonate_exec($ch);
var_dump(curl_impersonate_error($ch));
var_dump(curl_impersonate_errno($ch));
?>
--EXPECTF--
string(%d) "No URL set%A"
int(3)
