<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\PageNotFoundException;
use ReflectionMethod;


/*
 * Processes requests
 */
class Dispatcher {

    public function __construct(private readonly Router $router,
                                private Container $container)
    {

    }

    public function handle ($path): void
    {

        $params = $this->router->match($path);

        if ($params === false ){

            throw new PageNotFoundException("No route matched for '$path' ");
        }

        $action = $this->getActionName($params);
        $controller = $this->getControllerName($params);

        $controller_object = $this->container->get($controller);

        $args = $this->getActionArguments($controller,$action,$params);

        // Dynamically execute method
        $controller_object->$action(...$args);
    }

    private function getActionArguments(string $controller, string $action, array $params):array{

        $args = [];

        $method = new ReflectionMethod($controller, $action, );

        foreach($method->getParameters() as $parameter) {

            $name = $parameter->getName();

            $args[$name] = $params[$name] ;

        }

        return $args;
    }
    private function getControllerName (array $params):string {

        $controller = $params["controller"];

        $controller =str_replace('-', "",ucwords(strtolower($controller), '-'));

        $namespace =  (isset($params["namespace"])) ? "App\\Controllers\\{$params['namespace']}\\" :  "App\\Controllers\\";


        return  $namespace . $controller;

    }
    private function getActionName (array $params):string {

        $controller = $params["action"];

        return lcfirst(str_replace('-', "",ucwords(strtolower($controller), '-')));
    }


}