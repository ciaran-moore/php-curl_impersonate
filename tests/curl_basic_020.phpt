--TEST--
Test curl_impersonate_getinfo() function with CURLINFO_HTTP_CODE parameter
--CREDITS--
Jean-Marc Fontaine <jmf@durcommefaire.net>
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
  include 'server.inc';
  $host = curl_cli_server_start();

  $url = "{$host}/get.inc?test=";
  $ch  = curl_impersonate_init();
  curl_impersonate_setopt($ch, CURLOPT_URL, $url);
  curl_impersonate_exec($ch);
  var_dump(curl_impersonate_getinfo($ch, CURLINFO_HTTP_CODE));
?>
--EXPECT--
Hello World!
Hello World!int(200)
