--TEST--
Bug #73147: Use After Free in PHP7 unserialize()
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$poc = 'a:1:{i:0;O:19:"CURLImpersonateFile":1:{s:4:"name";R:1;}}';
try {
    var_dump(unserialize($poc));
} catch(Exception $e) {
    echo $e::class, ': ', $e->getMessage(), PHP_EOL;
}
?>
--EXPECT--
Exception: Unserialization of 'CURLImpersonateFile' is not allowed
