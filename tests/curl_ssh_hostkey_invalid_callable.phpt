--TEST--
Test curl_impersonate_setopt(_array)() with CURLOPT_SSH_HOSTKEYFUNCTION option
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
$curl_impersonate_version = curl_impersonate_version();
if ($curl_impersonate_version['version_number'] < 0x075400) {
    die("skip: blob options not supported for curl < 7.84.0");
}
?>
--FILE--
<?php

function testOption(CurlImpersonateHandle $handle, int $option) {
    try {
        var_dump(curl_impersonate_setopt($handle, $option, 'undefined'));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), PHP_EOL;
    }

    try {
        var_dump(curl_impersonate_setopt_array($handle, [$option => 'undefined']));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), PHP_EOL;
    }
}

$url = "https://example.com";
$ch = curl_impersonate_init($url);
testOption($ch, CURLOPT_SSH_HOSTKEYFUNCTION);

?>
--EXPECT--
TypeError: curl_impersonate_setopt(): Argument #3 ($value) must be a valid callback for option CURLOPT_SSH_HOSTKEYFUNCTION, function "undefined" not found or invalid function name
TypeError: curl_impersonate_setopt_array(): Argument #2 ($options) must be a valid callback for option CURLOPT_SSH_HOSTKEYFUNCTION, function "undefined" not found or invalid function name
