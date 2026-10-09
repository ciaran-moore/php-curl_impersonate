--TEST--
GH-15547 - curl_impersonate_multi_select overflow on timeout argument
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

$mh = curl_impersonate_multi_init();

try {
	curl_impersonate_multi_select($mh, -2500000);
} catch (\ValueError $e) {
	echo $e::class, ': ', $e->getMessage(), PHP_EOL;
}
curl_impersonate_multi_close($mh);
$mh = curl_impersonate_multi_init();
try {
	curl_impersonate_multi_select($mh, 2500000);
} catch (\ValueError $e) {
	echo $e::class, ': ', $e->getMessage(), PHP_EOL;
}
curl_impersonate_multi_close($mh);
$mh = curl_impersonate_multi_init();
var_dump(curl_impersonate_multi_select($mh, 1000000));
?>
--EXPECTF--
ValueError: curl_impersonate_multi_select(): Argument #2 ($timeout) must be between %d and %f
ValueError: curl_impersonate_multi_select(): Argument #2 ($timeout) must be between %d and %f
int(0)
