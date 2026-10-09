--TEST--
Test curl_impersonate_error() & curl_impersonate_errno() function with problematic host
--CREDITS--
TestFest 2009 - AFUP - Perrick Penet <perrick@noparking.net>
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
    $addr = "www.".uniqid().".invalid";
    if (gethostbyname($addr) != $addr) {
        print "skip catch all dns";
    }
?>
--FILE--
<?php

$url = "http://www.".uniqid().".invalid";
$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, $url);

curl_impersonate_exec($ch);
var_dump(curl_impersonate_error($ch));
var_dump(curl_impersonate_errno($ch));
?>
--EXPECTF--
%s resolve%s
int(6)
