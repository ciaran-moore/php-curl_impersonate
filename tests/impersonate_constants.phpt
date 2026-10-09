--TEST--
curl_impersonate constants: original names, CURLIMP* only when ext/curl has a different value
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
$all = get_defined_constants(true);
$own = $all['curl_impersonate'];
$ext = $all['curl'] ?? [];

$ok = true;
foreach ($own as $name => $value) {
    if (str_starts_with($name, 'CURLIMP')) {
        // A fallback name exists only because ext/curl owns the original name with another value
        $original = 'CURL' . substr($name, strlen('CURLIMP'));
        if (!array_key_exists($original, $ext) || $ext[$original] === $value) {
            echo "Unexpected fallback constant $name\n";
            $ok = false;
        }
    } elseif (array_key_exists($name, $ext)) {
        echo "$name registered by both extensions\n";
        $ok = false;
    }
}
var_dump($ok);

// curl-impersonate specific constants
var_dump(CURLOPT_IMPERSONATE, CURLOPT_HTTPBASEHEADER, CURLOPT_HTTP2_SETTINGS, CURLOPT_TLS_GREASE);
var_dump(CURLINFO_REDIRECT_HISTORY, CURLINFO_COOKIECHANGES);
var_dump(CURLFOLLOW_SAFE);
?>
--EXPECT--
bool(true)
int(10999)
int(11000)
int(11006)
int(1011)
int(4195305)
int(4195304)
int(4)
