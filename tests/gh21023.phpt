--TEST--
GH-21023 (crash with CURLOPT_XFERINFOFUNCTION set with an invalid callback)
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();
$url = "{$host}/get.inc";
$ch = curl_impersonate_init($url);
curl_impersonate_setopt($ch, CURLOPT_NOPROGRESS, 0);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_impersonate_setopt($ch, CURLOPT_XFERINFOFUNCTION, null);
curl_impersonate_exec($ch);
$ch = curl_impersonate_init($url);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_impersonate_setopt($ch, CURLOPT_PROGRESSFUNCTION, null);
curl_impersonate_exec($ch);
$ch = curl_impersonate_init($url);
curl_impersonate_setopt($ch, CURLOPT_WILDCARDMATCH, 1);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_impersonate_setopt($ch, CURLOPT_FNMATCH_FUNCTION, null);
curl_impersonate_exec($ch);
echo "OK", PHP_EOL;
?>
--EXPECT--
OK
