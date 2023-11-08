<?php

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.rawg.io/api/games?key=e4572f2dd14c4e218a32cef96255d08f",
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($ch);

$status_code = curl_getinfo($ch,CURLINFO_RESPONSE_CODE); // Integer Status code

curl_close($ch);

echo $status_code . "\n";

echo $response . "\n";