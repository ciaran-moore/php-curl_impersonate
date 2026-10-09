<?php

$ch = curl_impersonate_init("https://localhost");
curl_impersonate_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_impersonate_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$body = curl_impersonate_exec($ch);

if ($body !== "Caddy is up and running") {
    die("skip test needs Caddy");
}
