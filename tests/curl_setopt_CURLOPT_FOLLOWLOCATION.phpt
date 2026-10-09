--TEST--
CURLOPT_FOLLOWLOCATION values
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
include 'skipif-nocaddy.inc';
if (curl_impersonate_version()['version_number'] < 0x080d00) die('skip requires curl >= 8.13.0');
?>
--FILE--
<?php

foreach ([
	 0,
	 CURLFOLLOW_ALL,
	 CURLFOLLOW_OBEYCODE,
	 CURLFOLLOW_FIRSTONLY
	] as $follow) {
	$ch = curl_impersonate_init();
	curl_impersonate_setopt($ch,CURLOPT_URL, "https://localhost/redirect");
	curl_impersonate_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	var_dump(curl_impersonate_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow));
	var_dump(curl_impersonate_exec($ch));
}
?>
--EXPECTF--
bool(true)
string(%d) "%s"
bool(true)
string(%d) "%s"
bool(true)
string(%d) "%s"
bool(true)
string(%d) "%s"

