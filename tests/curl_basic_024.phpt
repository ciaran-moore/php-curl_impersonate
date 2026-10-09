--TEST--
Test curl_impersonate_getinfo() function with CURLINFO_* from curl >= 7.52.0
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

include 'server.inc';

$ch = curl_impersonate_init();
$host = curl_cli_server_start();

$url = "{$host}/get.inc?test=";
curl_impersonate_setopt($ch, CURLOPT_URL, $url);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_impersonate_exec($ch);
var_dump(CURLPROTO_HTTP === curl_impersonate_getinfo($ch, CURLINFO_PROTOCOL));
var_dump(0 === curl_impersonate_getinfo($ch, CURLINFO_PROXY_SSL_VERIFYRESULT));
var_dump(curl_impersonate_getinfo($ch, CURLINFO_SCHEME));
?>
--EXPECTF--
bool(true)
bool(true)
string(4) "%r(HTTP|http)%r"
