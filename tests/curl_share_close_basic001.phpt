--TEST--
curl_impersonate_share_close basic test
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

$sh = curl_impersonate_share_init();
//Show that there's a curl_share object
var_dump($sh);

curl_impersonate_share_close($sh);
var_dump($sh);

?>
--EXPECTF--
object(CurlImpersonateShareHandle)#1 (0) {
}

Deprecated: Function curl_impersonate_share_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
object(CurlImpersonateShareHandle)#1 (0) {
}
