--TEST--
GH-23814 (Curl callbacks keep their receiver alive when replacing or clearing themselves)
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
    public string $value = 'Still alive';

    public function __construct(private string $action, private CurlImpersonateHandle $handle) {}

    public function write(CurlImpersonateHandle $handle, string $data): int {
        if ($this->action === 'clear') {
            curl_impersonate_setopt_array($handle, [CURLOPT_WRITEFUNCTION => null]);
        } else {
            curl_impersonate_setopt($handle, CURLOPT_WRITEFUNCTION, static fn($handle, $data) => strlen($data));
        }
        gc_collect_cycles();
        echo $this->value, "\n";
        if ($this->action === 'throw') {
            throw new Exception('Callback exception');
        }
        return strlen($data);
    }

    public function __call(string $name, array $args): int {
        return $this->write(...$args);
    }

    public function __destruct() {
        echo "Destroyed\n";
    }
}

foreach (['replace' => 'write', 'clear' => 'missing', 'throw' => 'write'] as $action => $method) {
    echo "$method / $action\n";
    $handle = curl_impersonate_init('file://' . __FILE__);
    curl_impersonate_setopt($handle, CURLOPT_WRITEFUNCTION, [new Callback($action, $handle), $method]);
    try {
        var_dump(curl_impersonate_exec($handle));
    } catch (Exception $e) {
        echo $e->getMessage(), "\n";
    }
    unset($handle);
}
?>
--EXPECT--
write / replace
Still alive
Destroyed
bool(true)
missing / clear
Still alive
Destroyed
bool(true)
write / throw
Still alive
Destroyed
Callback exception
