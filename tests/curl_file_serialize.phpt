--TEST--
CURL file uploading
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$file = new CURLImpersonateFile(__DIR__ . '/curl_testdata1.txt');
var_dump(serialize($file));
?>
--EXPECTF--
Fatal error: Uncaught Exception: Serialization of 'CURLImpersonateFile' is not allowed in %s:%d
Stack trace:
#0 %s(%d): serialize(Object(CURLImpersonateFile))
#1 {main}
  thrown in %s on line %d
