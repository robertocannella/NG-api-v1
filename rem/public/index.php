<?php

declare(strict_types=1);

require dirname(__DIR__) . "/bootstrap.php";
//
$dotenv = new \Framework\Dotenv();
//
$dotenv->load(dirname(__DIR__) . "/.env");

set_error_handler("Framework\ErrorHandler::handleError");

set_exception_handler("Framework\ErrorHandler::handleException");

use Framework\Dispatcher;

$home_dir = '/rem/';
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($path === false){

    throw new UnexpectedValueException("Malformed URL: '{$_SERVER["REQUEST_URI"]}'");
}

$path = str_replace($home_dir,"", $path);

$router = require dirname(__DIR__) .  "/config/routes.php";

$container = require dirname(__DIR__) . "/config/services.php";

$dispatch = new Dispatcher($router, $container);

$dispatch->handle($path, $_SERVER["REQUEST_METHOD"]);





