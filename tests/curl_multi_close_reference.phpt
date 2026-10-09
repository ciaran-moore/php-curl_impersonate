--TEST--
curl_impersonate_multi_close closed by cleanup functions
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$mh = curl_impersonate_multi_init();
$array = array($mh);
$array[] = &$array;

curl_impersonate_multi_add_handle($mh, curl_impersonate_init());
curl_impersonate_multi_add_handle($mh, curl_impersonate_init());
curl_impersonate_multi_add_handle($mh, curl_impersonate_init());
curl_impersonate_multi_add_handle($mh, curl_impersonate_init());
echo "okey";
?>
--EXPECT--
okey
