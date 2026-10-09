--TEST--
Test curl_impersonate_error() & curl_impersonate_errno() function with problematic proxy
--CREDITS--
TestFest 2009 - AFUP - Perrick Penet <perrick@noparking.net>
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
    $addr = "www.".uniqid().".".uniqid();
    if (gethostbyname($addr) != $addr) {
        print "skip catch all dns";
    }
?>
--FILE--
<?php

$url = "http://www.example.org";
$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_PROXY, uniqid().":".uniqid());
curl_impersonate_setopt($ch, CURLOPT_URL, $url);

curl_impersonate_exec($ch);
var_dump(curl_impersonate_error($ch));
var_dump(curl_impersonate_errno($ch));
?>
--EXPECTF--
string(%d) "%r(Couldn't resolve proxy|Could not resolve proxy:|Could not resolve host:|Could not resolve:|Unsupported proxy syntax in)%r %s"
int(5)
