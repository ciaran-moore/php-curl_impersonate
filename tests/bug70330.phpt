--TEST--
Bug #70330 (Segmentation Fault with multiple "curl_impersonate_copy_handle")
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$t2 = curl_impersonate_init();
$t3 = curl_impersonate_copy_handle($t2);
$t3 = curl_impersonate_copy_handle($t2);
$t4 = curl_impersonate_init();
$t3 = curl_impersonate_copy_handle($t4);
$t5 = curl_impersonate_init();
$t6 = curl_impersonate_copy_handle($t5);
?>
okey
--EXPECT--
okey
