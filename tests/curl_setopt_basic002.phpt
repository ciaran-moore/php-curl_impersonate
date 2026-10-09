--TEST--
curl_impersonate_setopt basic tests with CURLOPT_STDERR.
--CREDITS--
Paul Sohier
#phptestfest utrecht
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

include 'server.inc';
$host = curl_cli_server_start();

// start testing
echo "*** Testing curl_impersonate_setopt with CURLOPT_STDERR\n";

$temp_file = tempnam(sys_get_temp_dir(), 'CURL_STDERR');

$handle = fopen($temp_file, 'w');

$url = "{$host}/";
$ch = curl_impersonate_init();

curl_impersonate_setopt($ch, CURLOPT_VERBOSE, 1);
curl_impersonate_setopt($ch, CURLOPT_STDERR, $handle);
$curl_content = curl_impersonate_exec($ch);

fclose($handle);
unset($handle);
var_dump(preg_replace('/[\r\n]/', ' ', file_get_contents($temp_file)));
@unlink($temp_file);

ob_start(); // start output buffering
$handle = fopen($temp_file, 'w');
curl_impersonate_setopt($ch, CURLOPT_URL, $url); //set the url we want to use
curl_impersonate_setopt($ch, CURLOPT_STDERR, $handle);
$data = curl_impersonate_exec($ch);
ob_end_clean();

fclose($handle);
unset($handle);
var_dump(preg_replace('/[\r\n]/', ' ', file_get_contents($temp_file)));
@unlink($temp_file);
?>
--EXPECTF--
*** Testing curl_impersonate_setopt with CURLOPT_STDERR
string(%d) "%S"
string(%d) "%S"
