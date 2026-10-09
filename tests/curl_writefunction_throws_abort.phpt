--TEST--
CURLOPT_WRITEFUNCTION aborts transfer when callback throws
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (!defined('CURLOPT_WRITEFUNCTION')) {
    die('skip CURLOPT_WRITEFUNCTION not available');
}
?>
--FILE--
<?php

include 'server.inc';
$host = curl_cli_server_start();
$ch = curl_impersonate_init("{$host}/get.inc");

echo "Test: write function throws exception\n";
curl_impersonate_setopt($ch, CURLOPT_WRITEFUNCTION,
    function (): int {
        throw new Exception('write exception');
    }
);

try {
    curl_impersonate_exec($ch);
} catch (Exception $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

var_dump(curl_impersonate_errno($ch) === CURLE_WRITE_ERROR);

echo "Test: write function is null\n";
curl_impersonate_setopt($ch, CURLOPT_WRITEFUNCTION, null);
curl_impersonate_exec($ch);
var_dump(curl_impersonate_errno($ch) === CURLE_OK);

?>
--EXPECTF--
Test: write function throws exception
Exception: write exception
bool(true)
Test: write function is null
Hello World!
Hello World!bool(true)
