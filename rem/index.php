<?php

declare(strict_types=1);

set_error_handler(function (
    int $errno,
    string $errstr,
    string $errfile,
    int $errline
): bool {
    throw new ErrorException($errstr,0, $errno, $errfile, $errline);
});

set_exception_handler(function (Throwable $exception) {

    if ($exception instanceof  Framework\Exceptions\PageNotFoundException){

        http_response_code(404);

        $template = '404.php';

    }else{

        http_response_code(500);

        $template = '500.php';
    }

    $show_errors = false;

    if ($show_errors){
        ini_set("display_errors", "1");
    }  else {
        ini_set("display_errors", "0");

//    error_reporting(E_ALL);
//
//    ini_set('error_log', '/var/www/html/api/v1/error.log');
//
//    ini_set( "log_errors", "1");

        error_log("Test");
        require "views/$template";
    }

    throw $exception;

});



require dirname(__DIR__) . "/rem/bootstrap.php";

use Framework\Exceptions\PageNotFoundException;
use Framework\Router;
use Framework\Dispatcher;

$home_dir = '/rem';
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($path === false){

    throw new UnexpectedValueException("Malformed URL: '{$_SERVER["REQUEST_URI"]}'");
}

$path = str_replace($home_dir,"", $path);

$router = new Router();

$router->add("/admin/{controller}/{action}",["namespace" => "Admin"]);
$router->add("/{title}/{id:\d+}/{page:\d+}", ["controller" => "products", "action" => "showPage"]);
$router->add("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
$router->add("{controller}/{id:\d+}/{action}");
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/",["controller" => "home", "action" => "index"]);
$router->add("/{controller}/{action}");


$container = new \Framework\Container;

// Bind the value of the database class to the service container
$container->set(App\Database::class, function () {

    return new \App\Database(
        $_ENV["REM_DB_HOST"],
        $_ENV["REM_DB_NAME"],
        $_ENV["REM_DB_USER"],
        $_ENV["REM_DB_PASS"]
    );

});

$dispatch = new Dispatcher($router, $container);

$dispatch->handle($path);





