<?php

// Ror coding bes-practices and easier debugging, user strict types.
declare(strict_types=1);

// Load environment, error handlers and defaults.
require __DIR__ . "/bootstrap.php";

// Allow only POST requests to this endpoint.  Set head for response information
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);
    header("Allow: POST");
    exit;
}

// All requests to this page are to made using JSON.
//      Read the data in (via php://input) and convert it to an associative array.
$data = (array) json_decode(file_get_contents("php://input"),true);


// Check for username and password presented
if ( ! array_key_exists("username", $data) ||
     ! array_key_exists("password", $data)){

    http_response_code(400);
    echo json_encode(["message" => "missing login credentials"]);
    exit;
}

// create database object
$database = new Database($_ENV["DB_HOST"],
    $_ENV["DB_NAME"],
    $_ENV["DB_USER"],
    $_ENV["DB_PASS"]);

// create user gateway object
$user_gateway = new UserGateway($database);

// get user
$user = $user_gateway->getByUsername($data["username"]);

// check if user was found:
if ( $user === false) {

    http_response_code(401); // Unauthorized
    echo json_encode(["message" => "invalid authentication"]);
    exit;
}

// check password from request with stored password_hash
if ( ! password_verify($data["password"], $user["password_hash"]) ) {

    http_response_code(401);
    echo json_encode(["message" => "invalid authentication"]);
    exit;
}

// start building the payload for access_token
// https://www.iana.org/assignments/jwt/jwt.xhtml for a list of valid claims
$payload = [
    "sub" => $user["id"],
    "name" => $user["name"]
];

// JSON Encode the payload:
$payload_json = json_encode($payload);

// create JWT
$codec = new JWTCodec( $_ENV["SECRET"]);
$access_token = $codec->encode($payload);

echo json_encode(["access_token" => $access_token]);
