--TEST--
Bug #79741: curl_impersonate_setopt CURLOPT_POSTFIELDS asserts on object with declared properties
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

class Test {
    public $prop = "value";
}

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_POSTFIELDS, new Test);

?>
===DONE===
--EXPECTF--
Fatal error: Uncaught Error: Object of class Test could not be converted to string in %s:%d
Stack trace:
#0 %s(%d): curl_impersonate_setopt(Object(CurlImpersonateHandle), %d, Object(Test))
#1 {main}
  thrown in %s on line %d
