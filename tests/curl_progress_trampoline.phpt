--TEST--
Test trampoline for curl option CURLOPT_PROGRESSFUNCTION
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

class TrampolineTest {
    public function __call(string $name, array $arguments) {
        static $done = false;
        if (!$done) {
            echo 'Trampoline for ', $name, PHP_EOL;
            $done = true;
        }
	    return CURL_PUSH_OK;
    }
}
$o = new TrampolineTest();
$callback = [$o, 'trampoline'];

$url = "{$host}/get.inc";
$ch = curl_impersonate_init($url);
curl_impersonate_setopt($ch, CURLOPT_NOPROGRESS, 0);
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_impersonate_setopt($ch, CURLOPT_PROGRESSFUNCTION, $callback);
echo curl_impersonate_exec($ch), PHP_EOL;

?>
--EXPECT--
Trampoline for trampoline
Hello World!
Hello World!
