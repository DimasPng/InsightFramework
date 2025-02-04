<?php

namespace App\Core;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Container
{
    protected array $bindings = [];
    protected array $singletons = [];

    public function bind(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    public function singleton(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
        $this->singletons[$key] = null;
    }

    /**
     * @template T
     * @param class-string<T> $key
     * @return T
     * @throws ReflectionException
     */
    public function make(string $key, array $parameters = []): object
    {
        if(array_key_exists($key, $this->singletons)) {
            if($this->singletons[$key] === null) {
                $this->singletons[$key] = call_user_func($this->bindings[$key], $this, $parameters);
            }
            return $this->singletons[$key];
        }

        if (isset($this->bindings[$key])) {
            return call_user_func($this->bindings[$key], $this, $parameters);
        }

        return $this->resolve($key);
    }

    public function has(string $key): bool
    {
        return isset($this->bindings[$key]) || isset($this->singletons[$key]);
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

     public function call(callable|array $callable, array $parameters = [])
     {
         if (is_array($callable)) {
             [$class, $method] = $callable;
             $instance = $this->make($class);
             return $this->callMethodWithDependencies($instance, $method, $parameters);
         }

         if (is_callable($callable)) {
             return call_user_func_array($callable, $parameters);
         }

         throw new Exception("Invalid callable provided");
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

     private function callMethodWithDependencies(object $instance, string $method, array $parameters = [])
     {
         $reflection = new ReflectionClass($instance);
         $methodReflection = $reflection->getMethod($method);

         $dependencies = [];
         foreach ($methodReflection->getParameters() as $parameter) {
             $name = $parameter->getName();
             $type = $parameter->getType();

             if (array_key_exists($name, $parameters)) {
                 $dependencies[] = $parameters[$name];
             } elseif ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                 $dependencies[] = $this->make($type->getName());
             } elseif ($parameter->isDefaultValueAvailable()) {
                 $dependencies[] = $parameter->getDefaultValue();
             } else {
                 throw new Exception("Cannot resolve parameter \${$name} in {$method}");
             }
         }

         return $methodReflection->invokeArgs($instance, $dependencies);
     }
}