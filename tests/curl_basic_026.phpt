--TEST--
Test curl_impersonate_getinfo() function with CURLOPT_* from curl >= 7.81.0
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php $curl_impersonate_version = curl_impersonate_version();
if ($curl_impersonate_version['version_number'] < 0x075100) {
    exit("skip: test works only with curl >= 7.81.0");
}
?>
--FILE--
<?php
include 'server.inc';

$ch = curl_impersonate_init();

$host = curl_cli_server_start();

$url = "{$host}/get.inc?test=";
curl_impersonate_setopt($ch, CURLOPT_URL, $url);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
var_dump(curl_impersonate_setopt($ch, CURLOPT_MIME_OPTIONS, CURLMIMEOPT_FORMESCAPE));
curl_impersonate_exec($ch);
?>
--EXPECT--
bool(true)
