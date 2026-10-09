--TEST--
curl_impersonate_copy_handle() allows to post CURLImpersonateFile multiple times
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

$filename = __DIR__ . '/curl_copy_handle_variation3.txt';
file_put_contents($filename, "Test.");
$file = curl_impersonate_file_create($filename);
$params = array('file' => $file);
var_dump(curl_impersonate_setopt($ch1, CURLOPT_POSTFIELDS, $params));

$ch2 = curl_impersonate_copy_handle($ch1);

var_dump(curl_impersonate_exec($ch1));
curl_impersonate_close($ch1);

var_dump(curl_impersonate_exec($ch2));
curl_impersonate_close($ch2);
?>
--EXPECTF--
bool(true)
string(%d) "curl_copy_handle_variation3.txt|application/octet-stream|5"

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
string(%d) "curl_copy_handle_variation3.txt|application/octet-stream|5"

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
--CLEAN--
<?php
@unlink(__DIR__ . '/curl_copy_handle_variation3.txt');
?>
