--TEST--
GH-18458 (authorization header is set despite CURLOPT_USERPWD set to null)
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
include 'skipif-nocaddy.inc';
?>
--FILE--
<?php

$ch = curl_impersonate_init("https://localhost/userpwd");
curl_impersonate_setopt($ch, CURLOPT_USERPWD, null);
curl_impersonate_setopt($ch, CURLOPT_VERBOSE, true);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($ch, CURLOPT_STDERR, fopen("php://stdout", "w"));
$response = curl_impersonate_exec($ch);
var_dump(str_contains($response, "authorization"));

$ch = curl_impersonate_init("https://localhost/username");
curl_impersonate_setopt($ch, CURLOPT_USERNAME, null);
curl_impersonate_setopt($ch, CURLOPT_PASSWORD, null);
curl_impersonate_setopt($ch, CURLOPT_VERBOSE, true);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($ch, CURLOPT_STDERR, fopen("php://stdout", "w"));
$response = curl_impersonate_exec($ch);
var_dump(str_contains($response, "authorization"));
?>
--EXPECTF--
%A
bool(false)
%A
bool(false)
