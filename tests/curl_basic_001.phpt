--TEST--
Test curl_impersonate_exec() function with basic functionality
--CREDITS--
Sebastian Deutsch <sebastian.deutsch@9elements.com>
TestFest 2009 - AFUP - Jean-Marc Fontaine <jmf@durcommefaire.net>
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
  include 'server.inc';
  $host = curl_cli_server_start();

  // start testing
  echo "*** Testing curl_impersonate_exec() : basic functionality ***\n";

  $url = "{$host}/get.inc?test=get";
  $ch = curl_impersonate_init();

  ob_start(); // start output buffering
  curl_impersonate_setopt($ch, CURLOPT_URL, $url); //set the url we want to use
  $ok = curl_impersonate_exec($ch);
  $curl_content = ob_get_contents();
  ob_end_clean();

  if($ok) {
    var_dump( $curl_content );
  } else {
    echo "curl_impersonate_exec returned false";
  }
?>
--EXPECT--
*** Testing curl_impersonate_exec() : basic functionality ***
string(25) "Hello World!
Hello World!"
