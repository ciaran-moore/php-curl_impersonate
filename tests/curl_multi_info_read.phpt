--TEST--
array curl_impersonate_multi_info_read ( resource $mh [, int &$msgs_in_queue = NULL ] );
--CREDITS--
marcosptf - <marcosptf@yahoo.com.br> - @phpsp - sao paulo - br
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$urls = array(
    "file://".__DIR__."/curl_testdata1.txt",
    "file://".__DIR__."/curl_testdata2.txt",
);

$mh = curl_impersonate_multi_init();
foreach ($urls as $i => $url) {
    $conn[$i] = curl_impersonate_init($url);
    curl_impersonate_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
    curl_impersonate_multi_add_handle($mh, $conn[$i]);
}

do {
    $status = curl_impersonate_multi_exec($mh, $active);
} while ($status === CURLM_CALL_MULTI_PERFORM || $active);

while ($info = curl_impersonate_multi_info_read($mh)) {
    var_dump($info);
}
?>
--EXPECTF--
array(3) {
  ["msg"]=>
  int(%d)
  ["result"]=>
  int(%d)
  ["handle"]=>
  object(CurlImpersonateHandle)#%d (0) {
  }
}
array(3) {
  ["msg"]=>
  int(%d)
  ["result"]=>
  int(%d)
  ["handle"]=>
  object(CurlImpersonateHandle)#%d (0) {
  }
}
