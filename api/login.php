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

// create JWT
$codec = new JWTCodec( $_ENV["SECRET"]);

// start building the payload for access_token
// https://www.iana.org/assignments/jwt/jwt.xhtml for a list of valid claims

require __DIR__ . "/token.php";

$refresh_token_gateway = new RefreshTokenGateway($database, $_ENV["SECRET"]);

$refresh_token_gateway->create($refresh_token, $refresh_token_expiry);
