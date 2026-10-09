--TEST--
Returning CURL_READFUNC_ABORT aborts the transfer
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_URL, "{$host}/get.inc");
curl_impersonate_setopt($ch, CURLOPT_POST, 1);
curl_impersonate_setopt($ch, CURLOPT_READFUNCTION, function () {
	return CURL_READFUNC_ABORT;
});
curl_impersonate_exec($ch);

echo "No output expected, because transfer was aborted by read function.\n";
?>
--EXPECT--
No output expected, because transfer was aborted by read function.
