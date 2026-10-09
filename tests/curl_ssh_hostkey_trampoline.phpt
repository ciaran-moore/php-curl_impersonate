--TEST--
Test trampoline for curl option CURLOPT_SSH_HOSTKEYFUNCTION
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
$curl_impersonate_version = curl_impersonate_version();
if ($curl_impersonate_version['version_number'] < 0x075400) {
    exit("skip: test works only with curl >= 7.84.0");
}
exit("skip: cannot properly test CURLOPT_SSH_HOSTKEYFUNCTION");
?>
--FILE--
<?php

class TrampolineTest {
    public function __call(string $name, array $arguments) {
        echo 'Trampoline for ', $name, PHP_EOL;
	    return CURLKHMATCH_MISMATCH;
    }
}
$o = new TrampolineTest();
$callback = [$o, 'trampoline'];

include 'server.inc';
$ch = curl_impersonate_init();

$host = curl_cli_server_start();

var_dump($host);
//$url = "scp://{$host}/get.inc";
//$url = "scp://example.com/example.txt";
curl_impersonate_setopt($ch, CURLOPT_URL, $url);
var_dump(curl_impersonate_setopt($ch, CURLOPT_SSH_HOSTKEYFUNCTION, $callback));
curl_impersonate_exec($ch);
?>
--EXPECT--
Trampoline for trampoline
FAKE
