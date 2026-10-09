--TEST--
Bug #48514 (cURL extension uses same resource name for simple and multi APIs)
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

$ch1 = curl_impersonate_init();
var_dump($ch1);
var_dump($ch1::class);

$ch2 = curl_impersonate_multi_init();
var_dump($ch2);
var_dump($ch2::class);

?>
--EXPECT--
object(CurlImpersonateHandle)#1 (0) {
}
string(21) "CurlImpersonateHandle"
object(CurlImpersonateMultiHandle)#2 (0) {
}
string(26) "CurlImpersonateMultiHandle"
