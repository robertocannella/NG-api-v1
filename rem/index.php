<?php

declare(strict_types=1);

require dirname(__DIR__) . "/rem/bootstrap.php";

use App\Models\Product;
use App\Controllers\Products;
use App\Controllers\Home;
use Framework\Router;
use Framework\Dispatcher;
use Framework\Viewer;

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

$dispatch = new Dispatcher($router, new Viewer());

$dispatch->handle($path);





