--TEST--
Bug #68089 (NULL byte injection - cURL lib)
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$url = "file:///etc/passwd\0http://example.com";
$ch = curl_impersonate_init();

try {
    curl_impersonate_setopt($ch, CURLOPT_URL, $url);
} catch (ValueError $exception) {
    echo $exception::class, ': ', $exception->getMessage(), "\n";
}

?>
Done
--EXPECT--
ValueError: curl_impersonate_setopt(): cURL option CURLOPT_URL must not contain any null bytes
Done
