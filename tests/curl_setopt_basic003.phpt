--TEST--
curl_impersonate_setopt() call with CURLOPT_HTTPHEADER
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
echo "*** curl_impersonate_setopt() call with CURLOPT_HTTPHEADER\n";

$url = "{$host}/";
$ch = curl_impersonate_init();

try {
    curl_impersonate_setopt($ch, CURLOPT_HTTPHEADER, 1);
} catch (TypeError $exception) {
    echo $exception::class, ': ', $exception->getMessage(), "\n";
}

$curl_content = curl_impersonate_exec($ch);
$ch = null;

var_dump( $curl_content );

$ch = curl_impersonate_init();

ob_start(); // start output buffering
curl_impersonate_setopt($ch, CURLOPT_HTTPHEADER, array());
curl_impersonate_setopt($ch, CURLOPT_URL, $host);

$curl_content = curl_impersonate_exec($ch);
ob_end_clean();
$ch = null;

var_dump( $curl_content );
?>
--EXPECT--
*** curl_impersonate_setopt() call with CURLOPT_HTTPHEADER
TypeError: curl_impersonate_setopt(): The CURLOPT_HTTPHEADER option must have an array value
bool(false)
bool(true)
