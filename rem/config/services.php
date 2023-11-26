<?php

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

$container->set(\Framework\TemplateViewerInterface::class, function (){


    return new \Framework\PHPTemplateViewer();
    //return new \Framework\MVCTemplateViewer();

});

return $container;