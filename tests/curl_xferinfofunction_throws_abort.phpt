--TEST--
CURLOPT_XFERINFOFUNCTION aborts transfer when callback throws
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (!defined('CURLOPT_XFERINFOFUNCTION')) {
    die('skip CURLOPT_XFERINFOFUNCTION not available');
}
?>
--FILE--
<?php

include 'server.inc';
$host = curl_cli_server_start();
$ch = curl_impersonate_init("{$host}/get.inc");

echo "Test: xfer info function throws exception\n";
curl_impersonate_setopt($ch, CURLOPT_NOPROGRESS, 0);
curl_impersonate_setopt($ch, CURLOPT_XFERINFOFUNCTION,
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

echo "Test: xfer info function is null\n";
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($ch, CURLOPT_XFERINFOFUNCTION, null);
curl_impersonate_exec($ch);
var_dump(curl_impersonate_errno($ch) === CURLE_OK);

?>
--EXPECTF--
Test: xfer info function throws exception
Exception: info exception
bool(true)
Test: xfer info function is null
bool(true)
