--TEST--
Bug #77946 (Errored cURL resources returned by curl_impersonate_multi_info_read() must be compatible with curl_impersonate_errno() and curl_impersonate_error())
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$urls = array(
   'unknown://scheme.tld',
);

$mh = curl_impersonate_multi_init();

foreach ($urls as $i => $url) {
    $conn[$i] = curl_impersonate_init($url);
    curl_impersonate_multi_add_handle($mh, $conn[$i]);
}

do {
    $status = curl_impersonate_multi_exec($mh, $active);
    $info = curl_impersonate_multi_info_read($mh);
    if (false !== $info) {
        var_dump($info['result']);
        var_dump(curl_impersonate_errno($info['handle']));
        var_dump(curl_impersonate_error($info['handle']));
    }
} while ($status === CURLM_CALL_MULTI_PERFORM || $active);

curl_impersonate_multi_close($mh);
?>
--EXPECTF--
int(1)
int(1)
string(%d) "Protocol %Sunknown%S %rnot supported( or disabled in libcurl)?%r"
