--TEST--
CURLOPT_HEADERFUNCTION aborts transfer when callback throws
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (!defined('CURLOPT_HEADERFUNCTION')) {
    die('skip CURLOPT_HEADERFUNCTION not available');
}
?>
--FILE--
<?php

include 'server.inc';
$host = curl_cli_server_start();
$ch = curl_impersonate_init("{$host}/get.inc");

echo "Test: header function throws exception\n";
curl_impersonate_setopt($ch, CURLOPT_HEADERFUNCTION,
    function (): int {
        throw new Exception('header exception');
    }
);

try {
    curl_impersonate_exec($ch);
} catch (Exception $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

var_dump(curl_impersonate_errno($ch) === CURLE_WRITE_ERROR);

echo "Test: header function is null\n";
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($ch, CURLOPT_HEADERFUNCTION, null);
curl_impersonate_exec($ch);
var_dump(curl_impersonate_errno($ch) === CURLE_OK);

?>
--EXPECTF--
Test: header function throws exception
Exception: header exception
bool(true)
Test: header function is null
bool(true)
