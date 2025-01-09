<?php

namespace App\Core;

class Route
{
    protected static Router $router;

    public static function init(Router $router): void
    {
        self::$router = $router;
    }

    public static function get(string $uri, array|string $action): void
    {
        self::$router->add('GET', $uri, $action);
    }

    public static function post(string $uri, array|string $action): void
    {
        self::$router->add('POST', $uri, $action);
    }

    public static function put(string $uri, array|string $action): void
    {
        self::$router->add('PUT', $uri, $action);
    }

    public static function patch(string $uri, array|string $action): void
    {
        self::$router->add('PATCH', $uri, $action);
    }

    public static function delete(string $uri, array|string $action): void
    {
        self::$router->add('DELETE', $uri, $action);
    }
}
