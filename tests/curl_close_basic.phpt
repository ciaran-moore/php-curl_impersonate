--TEST--
curl_impersonate_close
--CREDITS--
Stefan Koopmanschap <stefan@php.net>
#testfest Utrecht 2009
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$ch = curl_impersonate_init();
curl_impersonate_close($ch);
var_dump($ch);
?>
--EXPECTF--
Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
object(CurlImpersonateHandle)#%d (0) {
}
