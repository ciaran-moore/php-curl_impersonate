--TEST--
curl_impersonate_getinfo - CURLINFO_USED_PROXY
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
$curl_impersonate_version = curl_impersonate_version();
if ($curl_impersonate_version['version_number'] < 0x080700) die("skip: test works only with curl >= 8.7.0");
?>
--FILE--
<?php
include 'server.inc';

$host = curl_cli_server_start();
$port = (int) (explode(':', $host))[1];

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, "{$host}/get.inc?test=file");
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$info = curl_impersonate_getinfo($ch);
var_dump(isset($info['used_proxy']));
var_dump($info['used_proxy'] === 0); // this is always 0 before executing the transfer

$result = curl_impersonate_exec($ch);

$info = curl_impersonate_getinfo($ch);
var_dump(isset($info['used_proxy']));
var_dump($info['used_proxy'] === 0);
var_dump(curl_impersonate_getinfo($ch, CURLINFO_USED_PROXY) === $info['used_proxy']);

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
