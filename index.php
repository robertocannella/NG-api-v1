<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Exclude any parameters

$parts = explode('/',$path);

$resource = $parts[1];

$id = $parts[2];

$method = $_SERVER['REQUEST_METHOD'];

if ($resource != 'tasks'){
    // header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found!");
    http_response_code(404);
    exit();
}
echo $resource . $id . $method . "\n";




