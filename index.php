<?php

$ch = curl_init();

$headers = [
    "Authorization: token SECRET+KEY",
   // "User-Agent: robertocannella"
];

$payload = json_encode([
    "name" => "Create Via API",
    "description" => "A repository created via API"
]);

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.github.com/user/repos",
    CURLOPT_RETURNTRANSFER => true, //true to return the transfer as a string of the return value of curl_exec() instead of outputting it directly.
    CURLOPT_HTTPHEADER => $headers, // An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
    CURLOPT_USERAGENT => 'robertocannella',
    // CURLOPT_CUSTOMREQUEST => 'POST',  // optional when using POSTFIELDS
    // CURLOPT_POST => true,             // alternative to above, optional when using POSTFIELDS
    CURLOPT_POSTFIELDS => $payload
]);

$response = curl_exec($ch); // execute the request, saving the response

$status_code = curl_getinfo($ch,CURLINFO_RESPONSE_CODE); // Integer Status code

curl_close($ch);

echo $status_code . "\n";
echo $response . "\n";