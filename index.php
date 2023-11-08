<?php

$ch = curl_init();

$headers = [
    "Authorization: Client-ID GKjyF4RHv8V3zEyr4lZyNQ0PsLN3jgyzMBvNLqtIzRw"
];

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.unsplash.com/photos",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers

]);

$response = curl_exec($ch);

$status_code = curl_getinfo($ch,CURLINFO_RESPONSE_CODE); // Integer Status code

curl_close($ch);

echo $status_code . "\n";

echo $response . "\n";