<?php

declare(strict_types=1);

require dirname(__DIR__) . "/bootstrap.php";
//
$dotenv = new \Framework\Dotenv();
use Framework\Dispatcher;
use Framework\Request;
//
$dotenv->load(dirname(__DIR__) . "/.env");

set_error_handler("Framework\ErrorHandler::handleError");

set_exception_handler("Framework\ErrorHandler::handleException");

$router = require dirname(__DIR__) .  "/config/routes.php";

$container = require dirname(__DIR__) . "/config/services.php";

$middlewares = require dirname(__DIR__) . "/config/middleware.php";

$dispatch = new Dispatcher($router, $container, $middlewares);

$request = Request::createFromGlobals();

$response = $dispatch->handle($request);

$response->send();





