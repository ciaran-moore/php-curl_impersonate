--TEST--
Curl option CURLOPT_SSL_SIGNATURE_ALGORITHMS
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
$curl_impersonate_version = curl_impersonate_version();
if ($curl_impersonate_version['version_number'] < 0x080e00) die("skip: test works only with curl >= 8.14.0");

include 'skipif-nocaddy.inc';
?>
--FILE--
<?php

$ch = curl_impersonate_init('https://localhost/ping');
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

var_dump(curl_impersonate_exec($ch));

var_dump(curl_impersonate_setopt($ch, CURLOPT_SSL_SIGNATURE_ALGORITHMS, 'invalid-value'));
var_dump(curl_impersonate_exec($ch));
var_dump(curl_impersonate_error($ch));

var_dump(curl_impersonate_setopt($ch, CURLOPT_SSL_SIGNATURE_ALGORITHMS, 'ECDSA+SHA256:RSA+SHA256:DSA+SHA256:ed25519'));
var_dump(curl_impersonate_exec($ch));

var_dump(curl_impersonate_setopt($ch, CURLOPT_SSL_SIGNATURE_ALGORITHMS, null));
var_dump(curl_impersonate_exec($ch));

?>
--EXPECT--
string(4) "pong"
bool(true)
bool(false)
string(52) "failed setting signature algorithms: 'invalid-value'"
bool(true)
string(4) "pong"
bool(true)
string(4) "pong"
