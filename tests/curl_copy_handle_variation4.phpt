--TEST--
curl_impersonate_copy_handle() allows to post CURLImpersonateFile multiple times with curl_impersonate_multi_exec()
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$ch1 = curl_impersonate_init();
curl_impersonate_setopt($ch1, CURLOPT_SAFE_UPLOAD, 1);
curl_impersonate_setopt($ch1, CURLOPT_URL, "{$host}/get.php?test=file");
// curl_impersonate_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);

$filename = __DIR__ . '/curl_copy_handle_variation4.txt';
file_put_contents($filename, "Test.");
$file = curl_impersonate_file_create($filename);
$params = array('file' => $file);
var_dump(curl_impersonate_setopt($ch1, CURLOPT_POSTFIELDS, $params));

$ch2 = curl_impersonate_copy_handle($ch1);
$ch3 = curl_impersonate_copy_handle($ch1);

$mh = curl_impersonate_multi_init();
curl_impersonate_multi_add_handle($mh, $ch1);
curl_impersonate_multi_add_handle($mh, $ch2);
do {
    $status = curl_impersonate_multi_exec($mh, $active);
    if ($active) {
        curl_impersonate_multi_select($mh);
    }
} while ($active && $status == CURLM_OK);

curl_impersonate_multi_remove_handle($mh, $ch1);
curl_impersonate_multi_remove_handle($mh, $ch2);
curl_impersonate_multi_remove_handle($mh, $ch3);
curl_impersonate_multi_close($mh);
?>
===DONE===
--EXPECT--
bool(true)
curl_copy_handle_variation4.txt|application/octet-stream|5curl_copy_handle_variation4.txt|application/octet-stream|5===DONE===
--CLEAN--
<?php
@unlink(__DIR__ . '/curl_copy_handle_variation4.txt');
?>
