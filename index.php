<?php

$ch = curl_init();

$headers = [
    "Authorization: token YOUR+SECRET+KEY",
   // "User-Agent: robertocannella"
];

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.github.com/user/starred/httpie/cli",
    CURLOPT_RETURNTRANSFER => true, //true to return the transfer as a string of the return value of curl_exec() instead of outputting it directly.
    CURLOPT_HTTPHEADER => $headers, // An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
    CURLOPT_USERAGENT => 'robertocannella',
    CURLOPT_CUSTOMREQUEST => 'PUT'
]);

$response = curl_exec($ch); // execute the request, saving the response

$status_code = curl_getinfo($ch,CURLINFO_RESPONSE_CODE); // Integer Status code

curl_close($ch);

echo $status_code . "\n";
echo $response . "\n";