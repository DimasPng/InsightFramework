<?php

namespace App\Core;

use Exception;

class Container
{
    protected array $bindings = [];

    public function bind(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * @throws Exception
     */
    public function make(string $key): object
    {
        if (!isset($this->bindings[$key])) {
            throw new Exception("No service registered for key: {$key}");
        }

        return call_user_func($this->bindings[$key], $this);
    }

}