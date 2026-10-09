--TEST--
GH-23814 / GH-23860 (A curl callback can replace itself)
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (!in_array('file', curl_impersonate_version()['protocols'], true)) {
    die('skip file protocol not supported');
}
?>
--FILE--
<?php

$handle = curl_impersonate_init('file://' . __FILE__);
$callback = static function (CurlImpersonateHandle $handle, string $data): int {
    echo "Original callback\n";
    var_dump(curl_impersonate_setopt($handle, CURLOPT_WRITEFUNCTION, null));
    var_dump(curl_impersonate_setopt_array($handle, [CURLOPT_WRITEFUNCTION =>
        static function (CurlImpersonateHandle $handle, string $data): int {
            echo "Replacement callback\n";
            return strlen($data);
        },
    ]));

    return strlen($data);
};
curl_impersonate_setopt($handle, CURLOPT_WRITEFUNCTION, $callback);
var_dump(curl_impersonate_exec($handle));
var_dump(curl_impersonate_exec($handle));
var_dump(curl_impersonate_setopt($handle, CURLOPT_WRITEFUNCTION, null));
?>
--EXPECT--
Original callback
bool(true)
bool(true)
bool(true)
Replacement callback
bool(true)
bool(true)
