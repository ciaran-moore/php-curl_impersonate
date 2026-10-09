--TEST--
CURLOPT_PROGRESSFUNCTION aborts transfer when callback throws
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (!defined('CURLOPT_PROGRESSFUNCTION')) {
    die('skip CURLOPT_PROGRESSFUNCTION not available');
}
?>
--FILE--
<?php

include 'server.inc';
$host = curl_cli_server_start();
$ch = curl_impersonate_init("{$host}/get.inc");

echo "Test: progress function throws exception\n";
curl_impersonate_setopt($ch, CURLOPT_NOPROGRESS, 0);
curl_impersonate_setopt($ch, CURLOPT_PROGRESSFUNCTION,
    function (): int {
        throw new Exception('info exception');
    }
);

try {
    curl_impersonate_exec($ch);
} catch (Exception $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

var_dump(curl_impersonate_errno($ch) === CURLE_ABORTED_BY_CALLBACK);

echo "Test: progress function is null\n";
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($ch, CURLOPT_PROGRESSFUNCTION, null);
curl_impersonate_exec($ch);
var_dump(curl_impersonate_errno($ch) === CURLE_OK);

?>
--EXPECTF--
Test: progress function throws exception
Exception: info exception
bool(true)
Test: progress function is null
bool(true)
