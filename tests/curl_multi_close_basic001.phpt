--TEST--
curl_impersonate_multi_close return false when supplied resource not valid cURL multi handle
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$cmh = curl_impersonate_multi_init();
var_dump($cmh);
$multi_close_result = curl_impersonate_multi_close($cmh);
var_dump($multi_close_result);
var_dump($cmh);
curl_impersonate_multi_close($cmh);
?>
--EXPECT--
object(CurlImpersonateMultiHandle)#1 (0) {
}
NULL
object(CurlImpersonateMultiHandle)#1 (0) {
}
