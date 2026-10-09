--TEST--
curl_impersonate_setopt() basic parameter test
--CREDITS--
Paul Sohier
#phptestfest utrecht
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
echo "*** curl_impersonate_setopt() call with incorrect parameters\n";
$ch = curl_impersonate_init();

try {
    curl_impersonate_setopt($ch, '', false);
} catch (TypeError $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

try {
    curl_impersonate_setopt($ch, -10, 0);
} catch (ValueError $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

try {
    curl_impersonate_setopt($ch, 1000, 0);
} catch (ValueError $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

?>
--EXPECT--
*** curl_impersonate_setopt() call with incorrect parameters
TypeError: curl_impersonate_setopt(): Argument #2 ($option) must be of type int, string given
ValueError: curl_impersonate_setopt(): Argument #2 ($option) is not a valid cURL option
ValueError: curl_impersonate_setopt(): Argument #2 ($option) is not a valid cURL option
