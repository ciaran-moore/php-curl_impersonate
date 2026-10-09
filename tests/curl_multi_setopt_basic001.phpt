--TEST--
curl_impersonate_multi_setopt basic test
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

$mh = curl_impersonate_multi_init();
var_dump(curl_impersonate_multi_setopt($mh, CURLMOPT_PIPELINING, 0));

try {
    curl_impersonate_multi_setopt($mh, -1, 0);
} catch (ValueError $exception) {
    echo $exception::class, ': ', $exception->getMessage(), "\n";
}

?>
--EXPECT--
bool(true)
ValueError: curl_impersonate_multi_setopt(): Argument #2 ($option) is not a valid cURL multi option
