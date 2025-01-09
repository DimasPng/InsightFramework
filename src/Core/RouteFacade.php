<?php

namespace App\Core;

class RouteFacade
{
    protected static Router $router;
    protected static array $currentGroupAttributes = [];

    public static function init(Router $router): void
    {
        self::$router = $router;
    }

    public static function get(string $uri, array|string $action): Route
    {
        return self::$router->add('GET', $uri, $action);
    }

    public static function post(string $uri, array|string $action): Route
    {
        return self::$router->add('POST', $uri, $action);
    }

    public static function prefix(string $prefix): self
    {
        self::$currentGroupAttributes['prefix'] = trim($prefix, '/');
        return new static();
    }

    public function group(callable $routes): void
    {
        $attributes = self::$currentGroupAttributes;

        self::$currentGroupAttributes = [];

        self::$router->group($attributes, $routes);
    }
}