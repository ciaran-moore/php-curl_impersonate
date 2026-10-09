--TEST--
curl seek within uploaded file
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$url = "{$host}/get.inc?test=redirect&target=file";

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, $url);
curl_impersonate_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_impersonate_setopt($ch, CURLOPT_POSTFIELDS, [
    'file' => new CURLImpersonateFile(__DIR__ . '/curl_testdata1.txt')
]);
curl_impersonate_exec($ch);

?>
--EXPECT--
curl_testdata1.txt|application/octet-stream|6
