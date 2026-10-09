--TEST--
Bug #61948 (CURLOPT_COOKIEFILE '' raises open_basedir restriction)
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php if(substr(PHP_OS, 0, 3) == 'WIN' )
  die("skip Not Valid for Windows");
?>
--INI--
open_basedir="/tmp"
--FILE--
<?php
  $ch = curl_impersonate_init();
  var_dump(curl_impersonate_setopt($ch, CURLOPT_COOKIEFILE, ""));
  var_dump(curl_impersonate_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/foo"));
  var_dump(curl_impersonate_setopt($ch, CURLOPT_COOKIEFILE, "/xxx/bar"));
?>
--EXPECTF--
bool(true)
bool(true)

Warning: curl_impersonate_setopt(): open_basedir restriction in effect. File(/xxx/bar) is not within the allowed path(s): (/tmp) in %sbug61948-unix.php on line %d
bool(false)
