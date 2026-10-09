--TEST--
Bug #67643 (curl_impersonate_multi_getcontent returns '' when RETURNTRANSFER isn't set)
--EXTENSIONS--
curl_impersonate
--FILE--
<?php
    $ch = curl_impersonate_init();
    curl_impersonate_setopt($ch, CURLOPT_URL, 'file://'. __DIR__ . DIRECTORY_SEPARATOR .'curl_testdata1.txt');
    curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, false);

    $mh = curl_impersonate_multi_init();
    curl_impersonate_multi_add_handle($mh, $ch);

    $running = 0;
    do {
        curl_impersonate_multi_exec($mh, $running);
    } while($running > 0);

    $results = curl_impersonate_multi_getcontent($ch);

    curl_impersonate_multi_remove_handle($mh, $ch);
    curl_impersonate_multi_close($mh);

    var_dump($results);
?>
--EXPECT--
CURL1
NULL
