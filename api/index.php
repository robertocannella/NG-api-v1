<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Exclude any parameters

$parts = explode('/',$path);

$db = new Database($_ENV["DB_HOST"],$_ENV["DB_NAME"],$_ENV["DB_USER"],$_ENV["DB_PASS"]);

$user_gateway = new UserGateway($db);

$auth = new Auth($user_gateway);

if ( ! $auth->authenticateAPIKey() ) {
    exit;
}

$resource = $parts[2];

$id = $parts[3] ?? null;

$method = $_SERVER['REQUEST_METHOD'];

if ($resource != 'tasks'){

    http_response_code(404);

    exit();
}

$task_gateway = new TaskGateway($db);

$controller = new TaskController($task_gateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id );
