<?php

declare(strict_types=1);

require dirname(__DIR__) . "/vendor/autoload.php";

set_exception_handler([ErrorHandler::class, 'handleException']);

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

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

$db = new Database($_ENV["DB_HOST"],$_ENV["DB_NAME"],$_ENV["DB_USER"],$_ENV["DB_PASS"]);

$task_gateway = new TaskGateway($db);

$controller = new TaskController($task_gateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id );
