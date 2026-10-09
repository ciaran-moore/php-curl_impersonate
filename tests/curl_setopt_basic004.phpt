--TEST--
curl_impersonate_setopt() call with CURLOPT_RETURNTRANSFER
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
echo "*** curl_impersonate_setopt() call with CURLOPT_RETURNTRANSFER set to 1\n";

$url = "{$host}/";
$ch = curl_impersonate_init();

curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_impersonate_setopt($ch, CURLOPT_URL, $url);

$curl_content = curl_impersonate_exec($ch);
$ch = null;

var_dump( $curl_content );

echo "*** curl_impersonate_setopt() call with CURLOPT_RETURNTRANSFER set to 0\n";

$ch = curl_impersonate_init();

curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
curl_impersonate_setopt($ch, CURLOPT_URL, $url);
ob_start();
$curl_content = curl_impersonate_exec($ch);
ob_end_clean();
$ch = null;

var_dump( $curl_content );
?>
--EXPECTF--
*** curl_impersonate_setopt() call with CURLOPT_RETURNTRANSFER set to 1
string(%d) "%a"
*** curl_impersonate_setopt() call with CURLOPT_RETURNTRANSFER set to 0
bool(true)
