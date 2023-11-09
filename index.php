<?php

$ch = curl_init();

$headers = [
    "Authorization: Client-ID GKjyF4RHv8V3zEyr4lZyNQ0PsLN3jgyzMBvNLqtIzRw"
];

$response_headers = [];

/*
 * This closure callback to be called by CURLOPT_HEADERFUNCTION
 */
$header_callback = function ($ch,$header) use(&$response_headers) { // &= pass by reference

    $len = strlen($header); // get the length of the header

    $parts = explode(':',$header,2); // max 2 parts

    if ( count($parts) < 2 ){ // ignore invalid headers (less than 2 parts)
        return $len;
    }

    $response_headers[$parts[0]] = trim($parts[1]); // push the header k/v pair into the header array

    return $len; // for now, just return the len

};


curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.unsplash.com/photos",
    CURLOPT_RETURNTRANSFER => true, //true to return the transfer as a string of the return value of curl_exec() instead of outputting it directly.
    CURLOPT_HTTPHEADER => $headers, // An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
    CURLOPT_HEADERFUNCTION => $header_callback
]);

$response = curl_exec($ch); // execute the request, saving the response

$status_code = curl_getinfo($ch,CURLINFO_RESPONSE_CODE); // Integer Status code


curl_close($ch);

echo $status_code . "\n";
print_r($response_headers);
echo $response . "\n";