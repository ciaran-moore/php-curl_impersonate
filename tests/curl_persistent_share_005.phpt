--TEST--
Curl persistent share handles cannot be used with curl_impersonate_share_setopt
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

$sh = curl_impersonate_share_init_persistent([CURL_LOCK_DATA_DNS]);

try {
    curl_impersonate_share_setopt($sh, CURLOPT_SHARE, CURL_LOCK_DATA_CONNECT);
} catch (\TypeError $e) {
    echo $e::class, ': ', $e->getMessage(), PHP_EOL;
}

?>
--EXPECT--
TypeError: curl_impersonate_share_setopt(): Argument #1 ($share_handle) must be of type CurlImpersonateShareHandle, CurlImpersonateSharePersistentHandle given
