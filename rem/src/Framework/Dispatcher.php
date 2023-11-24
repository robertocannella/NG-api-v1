<?php

namespace Framework;

/*
 * Processes requests
 */
class Dispatcher {

    public function __construct(private readonly Router $router)
    {

    }

    public function handle ($path): void
    {

        $params = $this->router->match($path);

        if ($params === false ){

            exit("No route matched");
        }

        $segments = explode('/', $path);

        $controller = ucwords($params["controller"]);
        $action = ucwords($params["action"]);

        // Dynamically create object
        $controller_class = "App\\Controllers\\" . $controller;
        $controller_object = new $controller_class();

        // Dynamically execute method
        $controller_object->$action();
    }
}