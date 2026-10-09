--TEST--
Test curl_impersonate_multi_select()
--CREDITS--
Ivo Jansch <ivo@ibuildings.com>
#testfest Utrecht 2009
--EXTENSIONS--
curl_impersonate
--FILE--
<?php

//create the multiple cURL handle
$mh = curl_impersonate_multi_init();
echo curl_impersonate_multi_select($mh)."\n";

curl_impersonate_multi_close($mh);
?>
--EXPECTF--
%r(0|-1)%r
