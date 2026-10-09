--TEST--
Bug #80121: Null pointer deref if CurlImpersonateHandle directly instantiated
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

try {
    new CurlImpersonateHandle;
} catch (Error $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
try {
    new CurlImpersonateMultiHandle;
} catch (Error $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
try {
    new CurlImpersonateShareHandle;
} catch (Error $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

?>
--EXPECT--
Error: Cannot directly construct CurlImpersonateHandle, use curl_impersonate_init() instead
Error: Cannot directly construct CurlImpersonateMultiHandle, use curl_impersonate_multi_init() instead
Error: Cannot directly construct CurlImpersonateShareHandle, use curl_impersonate_share_init() instead
