--TEST--
Test curl_impersonate_copy_handle() with User Agent
--CREDITS--
Rick Buitenman <rick@meritos.nl>
#testfest Utrecht 2009
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

  include 'server.inc';
  $host = curl_cli_server_start();

  echo '*** Testing curl copy handle with User Agent ***' . "\n";

  $url = "{$host}/get.inc?test=useragent";
  $ch = curl_impersonate_init();

  ob_start(); // start output buffering
  curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_impersonate_setopt($ch, CURLOPT_USERAGENT, 'cURL phpt');
  curl_impersonate_setopt($ch, CURLOPT_URL, $url); //set the url we want to use

  $copy = curl_impersonate_copy_handle($ch);

  var_dump( curl_impersonate_exec($ch) );
  var_dump( curl_impersonate_exec($copy) );

  curl_impersonate_close($ch); // cannot close original handle before curl_impersonate_exec($copy) since it causes char * inputs to be invalid (see also: http://curl.haxx.se/libcurl/c/curl_easy_duphandle.html)
  curl_impersonate_close($copy);

?>
--EXPECTF--
*** Testing curl copy handle with User Agent ***
string(9) "cURL phpt"
string(9) "cURL phpt"

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
