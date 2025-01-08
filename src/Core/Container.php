<?php

namespace App\Core;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Container
{
    protected array $bindings = [];

    public function bind(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * @throws ReflectionException
     */
    public function make(string $key): object
    {
        if (isset($this->bindings[$key])) {
            return call_user_func($this->bindings[$key], $this);
        }

        return $this->resolve($key);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    protected function resolve(string $class): object
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $reflection->newInstanceArgs($dependencies);
     }

    /**
     * @throws Exception
     */
    protected function resolveDependencies(array $parameters): array
     {
         $dependencies = [];

         foreach ($parameters as $parameter) {
             $type = $parameter->getType();

             if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                 throw new Exception("Cannot resolve parameter {$parameter->getName()}.");
             }

             $dependencies[] = $this->make($type->getName());
         }

         return $dependencies;
     }
}