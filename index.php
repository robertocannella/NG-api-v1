<?php

$ch = curl_init();

$headers = [
    "Authorization: Client-ID GKjyF4RHv8V3zEyr4lZyNQ0PsLN3jgyzMBvNLqtIzRw"
];

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.unsplash.com/photos",
    CURLOPT_RETURNTRANSFER => true, //true to return the transfer as a string of the return value of curl_exec() instead of outputting it directly.
    CURLOPT_HTTPHEADER => $headers, // An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
    CURLOPT_HEADER => true  // 	true to include the header in the output.
]);

$response = curl_exec($ch); // execute the request, saving the response

$status_code = curl_getinfo($ch,CURLINFO_RESPONSE_CODE); // Integer Status code

$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

$content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

curl_close($ch);

echo $status_code . "\n";
echo $content_type . "\n";
echo $content_length . "\n";
echo $response . "\n";