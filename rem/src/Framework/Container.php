<?php

declare(strict_types=1);

namespace Framework;

use Closure;
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Container{

    private array $registery = [];

    public function set(string $name, Closure $value) : void
    {
        $this->registery[$name] = $value;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function get(string $class_name): object
    {
        if (array_key_exists($class_name, $this->registery)){

            return $this->registery[$class_name](); // need anonymous function to execute

        }

        $reflector = new ReflectionClass($class_name);

        $constructor = $reflector->getConstructor();

        $dependencies = [];

        if ($constructor === null) {

            return new $class_name;

        }

        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType();

            if ($type === null ){

                throw new InvalidArgumentException("Constructor parameter '{$parameter->getName()}' in the $class_name class has no type declaration");
            }
            if ( ! ($type instanceof ReflectionNamedType) ){

                throw new InvalidArgumentException("Constructor parameter '{$parameter->getName()}' is the $class_name class is an invalid type: '$type' - only single named types supported.");

            }

            if ($type->isBuiltin()){ // (string) (int) etc...

                throw new InvalidArgumentException ("Unable to resolve constructor parameter '{$parameter->getName()}' of type '$type' in the $class_name class. You may need an entry in the Service Container for '$class_name'.");

            }


            $dependencies[] = $this->get((string)$type);

        }

        return new $class_name(...$dependencies);
    }
}