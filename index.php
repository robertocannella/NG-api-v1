<?php
//phpinfo();
//exit();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Exclude any parameters

$parts = explode('/',$path);

$resource = $parts[1];

$id = $parts[2] ?? null;

$method = $_SERVER['REQUEST_METHOD'];

if ($resource != 'tasks'){

    http_response_code(404);

    exit();
}


require dirname(__DIR__) . "/v1/src/TaskController.php";


$controller = new TaskController();

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id );
