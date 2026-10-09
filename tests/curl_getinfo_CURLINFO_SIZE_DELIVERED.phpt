--TEST--
Curlinfo CURLINFO_SIZE_DELIVERED
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
$curl_impersonate_version = curl_impersonate_version();
if ($curl_impersonate_version['version_number'] < 0x081400) die("skip: test works only with curl >= 8.20.0");
?>
--FILE--
<?php
include 'server.inc';

$host = curl_cli_server_start();
$port = (int) (explode(':', $host))[1];

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, "{$host}/get.inc");
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$info = curl_impersonate_getinfo($ch);
var_dump(isset($info['size_delivered']));
var_dump($info['size_delivered'] === 0); // this is always 0 before executing the transfer

$result = curl_impersonate_exec($ch);

$info = curl_impersonate_getinfo($ch);
var_dump(isset($info['size_delivered']));
var_dump(is_int($info['size_delivered']));
var_dump(curl_impersonate_getinfo($ch, CURLINFO_SIZE_DELIVERED) === $info['size_delivered']);
var_dump(curl_impersonate_getinfo($ch, CURLINFO_SIZE_DELIVERED) > 0);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

