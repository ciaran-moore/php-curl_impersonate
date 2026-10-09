--TEST--
Impersonation turns on response decoding (CURLOPT_ACCEPT_ENCODING) unless the user set it
--EXTENSIONS--
curl_impersonate
zlib
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

function fetch(string $label, CurlImpersonateHandle $ch): void {
    curl_impersonate_setopt($ch, CURLOPT_URL, $GLOBALS['host'] . '/get.inc?test=gzip');
    curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $body = curl_impersonate_exec($ch);
    printf("%s: %s\n", $label, $body === 'Hello World!' ? 'decoded' : 'raw');
}

fetch('not impersonating', curl_impersonate_init());

$ch = curl_impersonate_init();
curl_impersonate_impersonate($ch, 'chrome136');
fetch('curl_impersonate_impersonate()', $ch);
fetch('copy', curl_impersonate_copy_handle($ch));

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_IMPERSONATE, 'chrome136');
fetch('CURLOPT_IMPERSONATE', $ch);

$ch = curl_impersonate_init();
curl_impersonate_impersonate($ch, 'chrome136', false);
fetch('without default headers', $ch);

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_IMPERSONATE, 'chrome136:no');
fetch('CURLOPT_IMPERSONATE :no', $ch);

$ch = curl_impersonate_init();
curl_impersonate_impersonate($ch, 'chrome136');
curl_impersonate_setopt($ch, CURLOPT_ACCEPT_ENCODING, null);
fetch('user null after impersonating', $ch);

$ch = curl_impersonate_init();
curl_impersonate_setopt($ch, CURLOPT_ACCEPT_ENCODING, null);
curl_impersonate_impersonate($ch, 'chrome136');
fetch('user null before impersonating', $ch);

ini_set('curl_impersonate.target', 'chrome136');
$ch = curl_impersonate_init();
fetch('curl_impersonate.target', $ch);
curl_impersonate_setopt($ch, CURLOPT_ACCEPT_ENCODING, null);
curl_impersonate_reset($ch);
fetch('after reset', $ch);
?>
--EXPECT--
not impersonating: raw
curl_impersonate_impersonate(): decoded
copy: decoded
CURLOPT_IMPERSONATE: decoded
without default headers: raw
CURLOPT_IMPERSONATE :no: raw
user null after impersonating: raw
user null before impersonating: raw
curl_impersonate.target: decoded
after reset: decoded
