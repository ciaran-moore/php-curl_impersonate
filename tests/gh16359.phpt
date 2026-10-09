--TEST--
GH-16359 - curl_impersonate_setopt with CURLOPT_WRITEFUNCTION and no user fn
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$log_file = tempnam(sys_get_temp_dir(), 'php-curl-CURLOPT_WRITEFUNCTION-trampoline');
$fp = fopen($log_file, 'w+');
fwrite($fp, "test");
$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_WRITEFUNCTION, null);
curl_impersonate_setopt($ch, CURLOPT_URL, 'file://' . $log_file);
curl_impersonate_exec($ch);
?>
--EXPECT--
test
