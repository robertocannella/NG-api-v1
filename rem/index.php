<?php

declare(strict_types=1);

require dirname(__DIR__) . "/rem/bootstrap.php";
//
$dotenv = new \Framework\Dotenv();
//
$dotenv->load(".env");

set_error_handler("Framework\ErrorHandler::handleError");

set_exception_handler("Framework\ErrorHandler::handleException");

use Framework\Dispatcher;

$home_dir = '/rem';
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($path === false){

    throw new UnexpectedValueException("Malformed URL: '{$_SERVER["REQUEST_URI"]}'");
}

$path = str_replace($home_dir,"", $path);

$router = require "config/routes.php";

$container = require "config/services.php";

$dispatch = new Dispatcher($router, $container);

$dispatch->handle($path);





