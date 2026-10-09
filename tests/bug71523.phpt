--TEST--
Bug #71523 (Copied handle with new option CURLOPT_HTTPHEADER crashes while curl_impersonate_multi_exec)
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (curl_impersonate_version()['version_number'] === 0x080a00) {
    // https://github.com/php/php-src/issues/15997
    die('xfail due to a libcurl bug');
}
?>
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$base = curl_impersonate_init($host);
curl_impersonate_setopt($base, CURLOPT_RETURNTRANSFER, true);
$mh = curl_impersonate_multi_init();

for ($i = 0; $i < 2; ++$i) {
    $ch = curl_impersonate_copy_handle($base);
    curl_impersonate_setopt($ch, CURLOPT_HTTPHEADER, ['Foo: Bar']);
    curl_impersonate_multi_add_handle($mh, $ch);
}

do {
    curl_impersonate_multi_exec($mh, $active);
} while ($active);
?>
okey
--EXPECT--
okey
