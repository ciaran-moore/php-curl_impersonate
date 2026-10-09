--TEST--
curl_impersonate accepts ext/curl's CURLFile and CURLStringFile
--EXTENSIONS--
curl
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

function upload(string $test, object $file): string {
    $ch = curl_impersonate_init("{$GLOBALS['host']}/get.inc?test={$test}");
    curl_impersonate_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $file]);
    curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return curl_impersonate_exec($ch);
}

$path = __DIR__ . '/curl_testdata1.txt';
var_dump(upload('file', new CURLFile($path, 'text/plain', 'ext-curl.txt')));
var_dump(upload('file', new CURLImpersonateFile($path, 'text/plain', 'own.txt')));
var_dump(upload('string_file', new CURLStringFile('abc', 'ext-curl.txt', 'text/plain')));
var_dump(upload('string_file', new CURLImpersonateStringFile('abc', 'own.txt', 'text/plain')));
var_dump(curl_impersonate_file_create($path) instanceof CURLImpersonateFile);
?>
--EXPECT--
string(25) "ext-curl.txt|text/plain|6"
string(20) "own.txt|text/plain|6"
string(56) "ext-curl.txt|text/plain|900150983cd24fb0d6963f7d28e17f72"
string(51) "own.txt|text/plain|900150983cd24fb0d6963f7d28e17f72"
bool(true)
