--TEST--
Bug #62839 (curl_impersonate_copy_handle segfault with CURLOPT_FILE)
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$curl = curl_impersonate_init();

$fd = tmpfile();
curl_impersonate_setopt($curl, CURLOPT_FILE, $fd);

curl_impersonate_copy_handle($curl);

echo 'DONE!';
?>
--EXPECT--
DONE!
