--TEST--
Test curl_impersonate_getinfo() function with CURLINFO_HTTP_VERSION parameter
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

include 'server.inc';

$ch = curl_impersonate_init();
var_dump(0 === curl_impersonate_getinfo($ch, CURLINFO_HTTP_VERSION));

$host = curl_cli_server_start();

$url = "{$host}/get.inc?test=";
curl_impersonate_setopt($ch, CURLOPT_URL, $url);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_impersonate_exec($ch);
var_dump(CURL_HTTP_VERSION_1_1 === curl_impersonate_getinfo($ch, CURLINFO_HTTP_VERSION));
?>
--EXPECT--
bool(true)
bool(true)
