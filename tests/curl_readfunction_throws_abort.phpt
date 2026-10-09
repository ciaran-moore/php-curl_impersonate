--TEST--
CURLOPT_READFUNCTION aborts transfer when callback throws
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (!defined('CURLOPT_READFUNCTION')) {
    die('skip CURLOPT_READFUNCTION not available');
}
?>
--FILE--
<?php

include 'server.inc';
$host = curl_cli_server_start();
$ch = curl_impersonate_init("{$host}/get.inc");

$file = new CURLImpersonateFile(__DIR__ . '/curl_testdata1.txt');
curl_impersonate_setopt($ch, CURLOPT_POST, 1);

echo "Test: read function throws exception\n";
curl_impersonate_setopt($ch, CURLOPT_READFUNCTION,
    function (): int {
        throw new Exception('read exception');
    }
);

try {
    curl_impersonate_exec($ch);
} catch (Exception $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

var_dump(curl_impersonate_errno($ch) === CURLE_ABORTED_BY_CALLBACK);

echo "Test: read function is null\n";
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($ch, CURLOPT_READFUNCTION, null);
curl_impersonate_exec($ch);
var_dump(curl_impersonate_errno($ch) === CURLE_OK);

?>
--EXPECTF--
Test: read function throws exception
Exception: read exception
bool(true)
Test: read function is null
bool(true)
