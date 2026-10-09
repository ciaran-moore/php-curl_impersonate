--TEST--
curl-impersonate specific CURLINFO_* values
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$ch = curl_impersonate_init("{$host}/get.inc?test=redirect&target=method&code=302");
curl_impersonate_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
var_dump(curl_impersonate_exec($ch));
var_dump(curl_impersonate_getinfo($ch, CURLINFO_REDIRECT_HISTORY));
var_dump(curl_impersonate_getinfo($ch, CURLINFO_COOKIECHANGES));
?>
--EXPECTF--
string(3) "GET"
array(1) {
  [0]=>
  string(%d) "302	http://localhost:%d/get.inc?test=redirect&target=method&code=302"
}
array(0) {
}
