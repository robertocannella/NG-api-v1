<?php

declare(strict_types=1);

require dirname(__DIR__) . "/rem/bootstrap.php";

use Framework\Router;
use Framework\Dispatcher;

$home_dir = '/rem';
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
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





