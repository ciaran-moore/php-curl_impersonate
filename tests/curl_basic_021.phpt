--TEST--
Test curl_impersonate_getinfo() function with CURLINFO_CONTENT_TYPE parameter
--CREDITS--
Jean-Marc Fontaine <jmf@durcommefaire.net>
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
  include 'server.inc';
  $host = curl_cli_server_start();
  $url  = "{$host}/get.inc?test=contenttype";

  $ch = curl_impersonate_init();
  curl_impersonate_setopt($ch, CURLOPT_URL, $url);
  curl_impersonate_exec($ch);
  var_dump(curl_impersonate_getinfo($ch, CURLINFO_CONTENT_TYPE));
?>
--EXPECT--
string(24) "text/plain;charset=utf-8"
