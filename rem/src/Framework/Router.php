<?php

namespace Framework;

class Router {

    private array $routes;
    public function __construct()
    {

    }
    public function add(string $path, array $params = []): void
    {
        $this->routes[] = [
            "path" => $path,
            "params" => $params
        ];
    }
    public function match(string $path): bool|array
    {
        $path = trim($path, "/");

        foreach ($this->routes as $route){

            //$pattern = "#^/(?P<controller>[a-z]+)/(?P<action>[a-z+]+)$#";

            $pattern = $this->getPatternFromRoutePath($route["path"]);
            //echo $pattern, "\n";

            if (preg_match($pattern, $path,$matches)){

                $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return array_merge($matches,$route["params"]);

            }
        }

        return false;

    }
    public function getPatternFromRoutePath (string $path): string{

        $path = trim($path, "/");

        $segments = explode("/", $path);

        $segments = array_map(function (string $segment):string {

            if (preg_match("#^\{([a-z][a-z0-9]*)\}$#", $segment, $matches)){

                return "(?P<" . $matches[1] . ">[^/]+)";

            }
            if (preg_match("#^\{([a-z][a-z0-9]*):(.+)\}$#", $segment, $matches)){

                return "(?P<" . $matches[1] . ">". $matches[2] . ")";

            }

            return $segment;

        }, $segments);

        return "#^" .  implode("/", $segments) . "$#iu"; // ignore case, utf-8 encoding


    }
}