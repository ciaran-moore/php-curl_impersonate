--TEST--
Bug #52202 (CURLOPT_PRIVATE gets clobbered)
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$curl = curl_impersonate_init();
curl_impersonate_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($curl, CURLOPT_PRIVATE, "123");
curl_impersonate_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
curl_impersonate_setopt($curl, CURLOPT_TIMEOUT, 1);
curl_impersonate_exec($curl);

var_dump(curl_impersonate_getinfo($curl, CURLINFO_PRIVATE));
?>
--EXPECT--
string(3) "123"
