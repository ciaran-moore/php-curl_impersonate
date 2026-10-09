--TEST--
Test curl_impersonate_getinfo() function with CURLINFO_EFFECTIVE_URL parameter
--CREDITS--
Jean-Marc Fontaine <jmf@durcommefaire.net>
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
  include 'server.inc';
  $host = curl_cli_server_start();

  $url = "http://{$host}/get.inc?test=";
  $ch  = curl_impersonate_init();

  curl_impersonate_setopt($ch, CURLOPT_URL, $url);
  curl_impersonate_exec($ch);
  $info = curl_impersonate_getinfo($ch, CURLINFO_EFFECTIVE_URL);
  var_dump($url == $info);
?>
--EXPECT--
Hello World!
Hello World!bool(true)
