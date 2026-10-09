--TEST--
Bug #72202 (curl_impersonate_close doesn't close cURL handle)
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$a = fopen(__FILE__, "r");
$b = $a;
var_dump($a, $b);
fclose($a);
var_dump($a, $b);
unset($a, $b);

$a = curl_impersonate_init();
$b = $a;
var_dump($a, $b);
curl_impersonate_close($a);
var_dump($a, $b);
unset($a, $b);
?>
--EXPECTF--
resource(%d) of type (stream)
resource(%d) of type (stream)
resource(%d) of type (Unknown)
resource(%d) of type (Unknown)
object(CurlImpersonateHandle)#1 (0) {
}
object(CurlImpersonateHandle)#1 (0) {
}

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
object(CurlImpersonateHandle)#1 (0) {
}
object(CurlImpersonateHandle)#1 (0) {
}
