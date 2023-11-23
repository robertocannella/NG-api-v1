<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use Framework\Models\Product;
use Framework\Controllers\Products;
use Framework\Controllers\Home;

if (isset($db)) {


    $action = ucwords($_GET["action"]);
    $controller = ucwords($_GET["controller"]);

    // Dynamically create object
    $controller_class = "Framework\\Controllers\\" . $controller;
    $controller_object = new $controller_class();

    // Dynamically execute method
    $controller_object->$action();


}





