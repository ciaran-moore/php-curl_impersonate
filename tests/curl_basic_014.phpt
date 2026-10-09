--TEST--
Test curl_impersonate_init() function with basic functionality
--CREDITS--
Jean-Marc Fontaine <jmf@durcommefaire.net>
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
  $ch = curl_impersonate_init();
  var_dump($ch);
?>
--EXPECT--
object(CurlImpersonateHandle)#1 (0) {
}
