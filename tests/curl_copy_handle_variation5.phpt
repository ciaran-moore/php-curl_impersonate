--TEST--
curl_impersonate_copy_handle() allows to post CURLImpersonateFile multiple times if postfields change
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$ch1 = curl_impersonate_init();
curl_impersonate_setopt($ch1, CURLOPT_SAFE_UPLOAD, 1);
curl_impersonate_setopt($ch1, CURLOPT_URL, "{$host}/get.php?test=file");
curl_impersonate_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);

$filename = __DIR__ . '/abc.txt';
file_put_contents($filename, "Test.");
$file = curl_impersonate_file_create($filename);
$params = array('file' => $file);
var_dump(curl_impersonate_setopt($ch1, CURLOPT_POSTFIELDS, $params));

$ch2 = curl_impersonate_copy_handle($ch1);

$filename = __DIR__ . '/def.txt';
file_put_contents($filename, "Other test.");
$file = curl_impersonate_file_create($filename);
$params = array('file' => $file);
var_dump(curl_impersonate_setopt($ch2, CURLOPT_POSTFIELDS, $params));

$ch3 = curl_impersonate_copy_handle($ch2);

var_dump(curl_impersonate_exec($ch1));
curl_impersonate_close($ch1);

var_dump(curl_impersonate_exec($ch2));
curl_impersonate_close($ch2);

var_dump(curl_impersonate_exec($ch3));
curl_impersonate_close($ch3);
?>
===DONE===
--EXPECTF--
bool(true)
bool(true)
string(%d) "abc.txt|application/octet-stream|5"

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
string(%d) "def.txt|application/octet-stream|11"

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
string(%d) "def.txt|application/octet-stream|11"

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
===DONE===
--CLEAN--
<?php
@unlink(__DIR__ . '/abc.txt');
@unlink(__DIR__ . '/def.txt');
?>
