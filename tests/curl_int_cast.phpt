--TEST--
Casting CurlImpersonateHandle to int returns object ID
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

$handle1 = curl_impersonate_init();
var_dump((int) $handle1);
$handle2 = curl_impersonate_init();
var_dump((int) $handle2);

// NB: Unlike resource IDs, object IDs are reused.
unset($handle2);
$handle3 = curl_impersonate_init();
var_dump((int) $handle3);

// Also works for CurlImpersonateMultiHandle.
$handle4 = curl_impersonate_multi_init();
var_dump((int) $handle4);

?>
--EXPECT--
int(1)
int(2)
int(2)
int(3)
