--TEST--
curl_impersonate_getinfo - CURLINFO_CERTINFO
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
include 'skipif-nocaddy.inc';
?>
--FILE--
<?php

$ch = curl_impersonate_init("https://localhost/ping");
curl_impersonate_setopt($ch, CURLOPT_CERTINFO, 1);
curl_impersonate_exec($ch);
echo "\n";

$certinfo = curl_impersonate_getinfo($ch, CURLINFO_CERTINFO);

var_dump(empty($certinfo));
var_dump(str_starts_with($certinfo[0]['Cert'], '-----BEGIN CERTIFICATE-----'));

?>
--EXPECT--
pong
bool(false)
bool(true)
