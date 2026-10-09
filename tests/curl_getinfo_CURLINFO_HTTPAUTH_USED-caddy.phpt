--TEST--
curl_impersonate_getinfo - CURLINFO_HTTPAUTH_USED - online test
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
$curl_impersonate_version = curl_impersonate_version();
if ($curl_impersonate_version['version_number'] < 0x080c00) die("skip: test works only with curl >= 8.12.0");
include 'skipif-nocaddy.inc';
?>
--FILE--
<?php

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, "https://localhost/http-basic-auth");
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$info = curl_impersonate_getinfo($ch);
var_dump($info['httpauth_used'] === 0); // this is always 0 before executing the transfer

curl_impersonate_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC | CURLAUTH_DIGEST);
curl_impersonate_setopt($ch, CURLOPT_USERNAME, "foo");
curl_impersonate_setopt($ch, CURLOPT_PASSWORD, "bar");

$result = curl_impersonate_exec($ch);
$info = curl_impersonate_getinfo($ch);

var_dump($info['httpauth_used'] === CURLAUTH_BASIC);

?>
--EXPECT--
bool(true)
bool(true)
