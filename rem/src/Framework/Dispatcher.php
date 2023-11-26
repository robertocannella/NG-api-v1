<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\PageNotFoundException;

use ReflectionMethod;
use UnexpectedValueException;


/*
 * Processes requests
 */
class Dispatcher {

    public function __construct(
        private readonly Router $router,
        private Container $container,
        private array $middleware_classes)
    {

    }

    public function handle (Request $request): Response
    {
        $path = $this->getPath($request->uri);

        $params = $this->router->match($path, $request->method);

        if ($params === false) {

            throw new PageNotFoundException("No route matched for '$path' with method '{$request->method}' ");
        }

        $action = $this->getActionName($params);
        $controller = $this->getControllerName($params);

        $controller_object = $this->container->get($controller);

        // Changed To Twig
        $controller_object->setViewer($this->container->get(TemplateViewerInterface::class));

        $controller_object->setResponse($this->container->get(Response::class));

        $args = $this->getActionArguments($controller, $action, $params);

        $controller_handler = new ControllerRequestHandler($controller_object, $action, $args);

        $middleware = $this->getMiddleware($params);

        $middleware_handler = new MiddlewareRequestHandler($middleware, $controller_handler);

        return $middleware_handler->handle($request);
        //return $controller_handler->handle($request);

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
    private function getPath(string $uri): string
    {

        $home_dir = '/rem/';
        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === false){

            throw new UnexpectedValueException("Malformed URL: '{$uri}'");
        }

        return str_replace($home_dir,"", $path);

    }
    private function getMiddleware(array $params) : array
    {
        if ( ! array_key_exists( "middleware", $params) ){

            return [];
        }

        $middleware = explode("|", $params["middleware"]);

        array_walk($middleware,function (&$value){

            if (! array_key_exists($value, $this->middleware_classes)){

                throw new UnexpectedValueException("Middleware '$value' not found in config settings");
            }
            $value = $this->container->get($this->middleware_classes[$value]);

        });
        return $middleware;
    }


}