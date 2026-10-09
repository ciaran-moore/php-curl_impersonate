--TEST--
Callable options are nullable
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

$ch = curl_impersonate_init();
curl_impersonate_setopt_array($ch, [
    CURLOPT_PROGRESSFUNCTION => null,
]);

?>
===DONE===
--EXPECT--
===DONE===
