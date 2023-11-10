<?php

declare(strict_types=1);

require dirname(__DIR__) . "/v1/vendor/autoload.php";

set_exception_handler([ErrorHandler::class, 'handleException']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Exclude any parameters

$parts = explode('/',$path);

$resource = $parts[1];

$id = $parts[2] ?? null;

$method = $_SERVER['REQUEST_METHOD'];

if ($resource != 'tasks'){

    http_response_code(404);

    exit();
}

header("Content-type: application/json; charset: UTF-8");

$controller = new TaskController();

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id );
