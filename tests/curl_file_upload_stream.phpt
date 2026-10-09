--TEST--
CURL file uploading from stream
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_SAFE_UPLOAD, 1);
curl_impersonate_setopt($ch, CURLOPT_URL, "{$host}/get.inc?test=file");
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$file = curl_impersonate_file_create('data://text/plain;base64,SSBsb3ZlIFBIUAo=', 'text/plain', 'i-love-php');
$params = array('file' => $file);
var_dump(curl_impersonate_setopt($ch, CURLOPT_POSTFIELDS, $params));

var_dump(curl_impersonate_exec($ch));
?>
--EXPECT--
bool(true)
string(24) "i-love-php|text/plain|11"
