--TEST--
Bug #66109 (Option CURLOPT_CUSTOMREQUEST can't be reset to default.)
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();
$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, "{$host}/get.inc?test=method");
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_impersonate_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
var_dump(curl_impersonate_exec($ch));

curl_impersonate_setopt($ch, CURLOPT_CUSTOMREQUEST, NULL);
var_dump(curl_impersonate_exec($ch));
?>
--EXPECT--
string(6) "DELETE"
string(3) "GET"
