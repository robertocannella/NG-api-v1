<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use Framework\Models\Product;
use Framework\Controllers\Products;
use Framework\Controllers\Home;

if (isset($db)) {


    $action = $_GET["action"];
    $controller = $_GET["controller"];



    if ($controller === "products"){
        $model = new Product($db);
        $controller_object = new Products($model);

    }else{
        $controller_object = new Home();

    }


    if ($action === "index"){
        $controller_object->index();
    }elseif($action === "show"){
        $controller_object->show();
    }


}





