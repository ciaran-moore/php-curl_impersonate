--TEST--
Test curl_impersonate_init() function with $url parameter defined
--CREDITS--
Jean-Marc Fontaine <jmf@durcommefaire.net>
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
  $url = 'http://www.example.com/';
  $ch  = curl_impersonate_init($url);
  var_dump($url == curl_impersonate_getinfo($ch, CURLINFO_EFFECTIVE_URL));
?>
--EXPECT--
bool(true)
