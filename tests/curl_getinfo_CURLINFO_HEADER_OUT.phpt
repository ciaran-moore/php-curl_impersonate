--TEST--
curl_impersonate_getinfo CURLINFO_HEADER_OUT
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$ch = curl_impersonate_init("{$host}/get.inc?test=method");
curl_impersonate_setopt($ch, CURLINFO_HEADER_OUT, 1);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_impersonate_setopt($ch, CURLOPT_HTTPHEADER, ['Request-num: 1']);
curl_impersonate_exec($ch);

// The header string contains the full request header line(s).
$headers = rtrim(curl_impersonate_getinfo($ch, CURLINFO_HEADER_OUT));
echo "With CURLINFO_HEADER_OUT=1:\n";
echo $headers, "\n---\n";

// Toggling back to 0 should clear the buffer; next getinfo returns false.
curl_impersonate_setopt($ch, CURLOPT_HTTPHEADER, ['Request-num: 2']);
curl_impersonate_setopt($ch, CURLINFO_HEADER_OUT, 0);
curl_impersonate_exec($ch);

$headers = curl_impersonate_getinfo($ch, CURLINFO_HEADER_OUT);
echo "With CURLINFO_HEADER_OUT=0:\n";
var_dump($headers);

?>
--EXPECTF--
With CURLINFO_HEADER_OUT=1:
GET /get.inc?test=method HTTP/1.1
Host: localhost:%d
Request-num: 1
---
With CURLINFO_HEADER_OUT=0:
bool(false)
