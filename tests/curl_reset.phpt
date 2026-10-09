--TEST--
Test curl_impersonate_reset
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

$test_file = tempnam(sys_get_temp_dir(), 'php-curl-test');
$log_file = tempnam(sys_get_temp_dir(), 'php-curl-test');

$fp = fopen($log_file, 'w+');
fwrite($fp, "test");
fclose($fp);

$testfile_fp = fopen($test_file, 'w+');

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_FILE, $testfile_fp);
curl_impersonate_setopt($ch, CURLOPT_URL, 'file://' . $log_file);
curl_impersonate_exec($ch);

curl_impersonate_reset($ch);
curl_impersonate_setopt($ch, CURLOPT_URL, 'file://' . $log_file);
curl_impersonate_exec($ch);

fclose($testfile_fp);

echo file_get_contents($test_file);

// cleanup
unlink($test_file);
unlink($log_file);
?>
--EXPECT--
testtest
