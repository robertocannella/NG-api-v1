<?php

$payload = [
    "sub" => $user["id"], //https://www.rfc-editor.org/rfc/rfc7519.html#section-4.1.2
    "exp" => time() + 20, // https://www.rfc-editor.org/rfc/rfc7519.html#section-4.1.4
    "name" => $user["name"]
];

// JSON Encode the payload:
$payload_json = json_encode($payload);

$access_token = $codec->encode($payload);

$refresh_token_expiry = time() * 432000; //5 days

$refresh_token = $codec->encode([
    "sub" => $user["id"],
    "exp" => $refresh_token_expiry
]);

echo json_encode([
    "access_token" => $access_token,
    "refresh_token" => $refresh_token
]);