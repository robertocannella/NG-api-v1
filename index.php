<?php

use GuzzleHttp\Exception\GuzzleException;

require __DIR__ . "/vendor/autoload.php";

$client = new GuzzleHttp\Client;

try {
   $response =  $client->request("GET", "https://api.github.com/user/repos",[
        "headers" => [
            "Authorization: Bearer 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",
            "User-Agent: RobertoCannella",
        ],
       "debug" => true // dump all headers
    ]);

} catch (GuzzleException $e) {
    echo $e->getMessage();
}
if (isset($response)) {
    print_r($response->getHeaders());
    print_r($response->getBody());
} else {
    echo "No response.\n";
}



