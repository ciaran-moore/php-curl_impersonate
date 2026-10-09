--TEST--
GH-16802 (open_basedir bypass using curl extension)
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
$curl_impersonate_version = curl_impersonate_version();
if ($curl_impersonate_version['version_number'] < 0x075500) {
    die("skip: blob options not supported for curl < 7.85.0");
}
?>
--INI--
open_basedir=/nowhere
--FILE--
<?php
$ch = curl_impersonate_init("file:///etc/passwd");
curl_impersonate_setopt($ch, CURLOPT_PROTOCOLS_STR, "all");
curl_impersonate_setopt($ch, CURLOPT_PROTOCOLS_STR, "ftp,all");
curl_impersonate_setopt($ch, CURLOPT_PROTOCOLS_STR, "all,ftp");
curl_impersonate_setopt($ch, CURLOPT_PROTOCOLS_STR, "all,file,ftp");
var_dump(curl_impersonate_exec($ch));
?>
--EXPECTF--
Warning: curl_impersonate_setopt(): The FILE protocol cannot be activated when an open_basedir is set in %s on line %d

Warning: curl_impersonate_setopt(): The FILE protocol cannot be activated when an open_basedir is set in %s on line %d

Warning: curl_impersonate_setopt(): The FILE protocol cannot be activated when an open_basedir is set in %s on line %d

Warning: curl_impersonate_setopt(): The FILE protocol cannot be activated when an open_basedir is set in %s on line %d
bool(false)
