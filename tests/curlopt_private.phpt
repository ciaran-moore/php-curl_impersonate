--TEST--
CURLOPT_PRIVATE
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$curl = curl_impersonate_init("foobar");
$obj = new stdClass;
curl_impersonate_setopt($curl, CURLOPT_PRIVATE, $obj);
var_dump($obj === curl_impersonate_getinfo($curl, CURLINFO_PRIVATE));

$curl2 = curl_impersonate_copy_handle($curl);
var_dump($obj === curl_impersonate_getinfo($curl2, CURLINFO_PRIVATE));
$obj2 = new stdClass;
curl_impersonate_setopt($curl2, CURLOPT_PRIVATE, $obj2);
var_dump($obj === curl_impersonate_getinfo($curl, CURLINFO_PRIVATE));
var_dump($obj2 === curl_impersonate_getinfo($curl2, CURLINFO_PRIVATE));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
