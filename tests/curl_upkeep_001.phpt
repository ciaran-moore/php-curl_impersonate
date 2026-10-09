--TEST--
curl_impersonate_upkeep() function
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (curl_impersonate_version()['version_number'] < 0x073e00) die('skip requires curl >= 7.62.0');
?>
--FILE--
<?php
include 'server.inc';

$host = curl_cli_server_start();

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch,CURLOPT_URL,$host);
curl_impersonate_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_impersonate_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_2_0);
curl_impersonate_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_impersonate_setopt($ch, CURLOPT_UPKEEP_INTERVAL_MS, 200);
if (curl_impersonate_exec($ch)) {
    usleep(300);
    var_dump(curl_impersonate_upkeep($ch));
}
?>
--EXPECT--
bool(true)
