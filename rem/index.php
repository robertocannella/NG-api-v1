<?php

declare(strict_types=1);

require dirname(__DIR__) . "/rem/bootstrap.php";

use App\Models\Product;
use App\Controllers\Products;
use App\Controllers\Home;
use Framework\Router;

$home_dir = '/rem';
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = str_replace($home_dir,"", $path);


$router = new Router();

$router->add("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
$router->add("{controller}/{id:\d+}/{action}");
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/",["controller" => "home", "action" => "index"]);
$router->add("/{controller}/{action}");

$params = $router->match($path);

if ($params === false ){

    exit("No route matched");
}

$segments = explode('/', $path);

if (isset($db)) {

    $controller = ucwords($params["controller"]);
    $action = ucwords($params["action"]);

    // Dynamically create object
    $controller_class = "App\\Controllers\\" . $controller;
    $controller_object = new $controller_class();

    // Dynamically execute method
    $controller_object->$action();
}





