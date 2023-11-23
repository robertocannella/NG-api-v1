<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use Framework\Models\Model;
use Framework\Controllers\Controller;

if (isset($db)) {
    $model = new Model($db);
    $controller = new Controller($model);

    $controller->index();
}





