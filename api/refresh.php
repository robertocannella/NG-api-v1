<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);
    header("Allow: POST");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"),true);

if ( ! array_key_exists("token", $data)) {

    http_response_code(400);
    echo json_encode(["message" => "missing token"]);
    exit;
}

$codec = new JWTCodec($_ENV["SECRET"]);

try {

    $payload = $codec->decode($data["token"]);

} catch (Exception $e) {

    http_response_code(400);
    echo json_encode(["message"=>"invalid token"]);
    exit;
}
$user_id = $payload["sub"];
// create database object
$database = new Database($_ENV["DB_HOST"],
    $_ENV["DB_NAME"],
    $_ENV["DB_USER"],
    $_ENV["DB_PASS"]);

$refresh_token_gateway = new RefreshTokenGateway($database, $_ENV["SECRET"]);

$refresh_token = $refresh_token_gateway->getByToken($data["token"]);

if ( $refresh_token === false){

    http_response_code(400);
    echo json_encode(["message"=> "invalid token (not in allow-list)"]);
    exit;
}
// create user gateway object
$user_gateway = new UserGateway($database);
$user = $user_gateway->getById($user_id);

if ($user === false) {

    http_response_code(401);
    echo json_encode(["message"=>"invalid authentication"]);
    exit;

}

// At this point user exists in the database.  Here, add logic to validate user's access to the api.
//      Adding a boolean flag in the database or other logic.

require __DIR__ . "/token.php";


$refresh_token_gateway->delete($data["token"]);

$refresh_token_gateway->create($refresh_token, $refresh_token_expiry);