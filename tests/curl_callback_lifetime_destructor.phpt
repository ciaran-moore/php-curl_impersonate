--TEST--
GH-23814 (Curl callback receivers are destroyed with the callback guard still set)
--EXTENSIONS--
curl_impersonate
--SKIPIF--
<?php
if (!in_array('file', curl_impersonate_version()['protocols'], true)) {
    die('skip file protocol not supported');
}
?>
--FILE--
<?php
class Callback {
    public function __construct(private CurlImpersonateHandle $handle) {}

    public function write(CurlImpersonateHandle $handle, string $data): int {
        curl_impersonate_setopt($handle, CURLOPT_WRITEFUNCTION, null);
        echo "Callback returning\n";
        return strlen($data);
    }

    public function __destruct() {
        foreach (['curl_impersonate_reset', 'curl_impersonate_close'] as $function) {
            try {
                // curl_impersonate_close() is deprecated in PHP 8.5.
                $function($this->handle);
            } catch (Error $e) {
                echo $e->getMessage(), "\n";
            }
        }
        curl_impersonate_setopt($this->handle, CURLOPT_WRITEFUNCTION,
            static function (CurlImpersonateHandle $handle, string $data): int {
                echo "Callback installed by destructor\n";
                return strlen($data);
            });
    }
}

$handle = curl_impersonate_init('file://' . __FILE__);
curl_impersonate_setopt($handle, CURLOPT_WRITEFUNCTION, [new Callback($handle), 'write']);
var_dump(curl_impersonate_exec($handle));
var_dump(curl_impersonate_exec($handle));
curl_impersonate_reset($handle);
echo "Reset outside callback succeeded\n";
?>
--EXPECTF--
Callback returning
curl_impersonate_reset(): Attempt to reset cURL handle from a callback

Deprecated: Function curl_impersonate_close() is deprecated since 8.5, as it has no effect since PHP 8.0 in %s on line %d
curl_impersonate_close(): Attempt to close cURL handle from a callback
bool(true)
Callback installed by destructor
bool(true)
Reset outside callback succeeded
