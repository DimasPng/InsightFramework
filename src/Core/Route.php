<?php

namespace App\Core;

class Route
{
    protected static Router $router;
    protected static string $currentPrefix = '';

    public static function init(Router $router): void
    {
        self::$router = $router;
    }

    public static function get(string $uri, array|string $action): void
    {
        self::$router->add('GET', self::applyPrefix($uri), $action);
    }

    public static function post(string $uri, array|string $action): void
    {
        self::$router->add('POST', self::applyPrefix($uri), $action);
    }

    public static function put(string $uri, array|string $action): void
    {
        self::$router->add('PUT', self::applyPrefix($uri), $action);
    }

    public static function patch(string $uri, array|string $action): void
    {
        self::$router->add('PATCH', self::applyPrefix($uri), $action);
    }

    public static function delete(string $uri, array|string $action): void
    {
        self::$router->add('DELETE', self::applyPrefix($uri), $action);
    }

    public static function prefix(string $prefix): self
    {
        self::$currentPrefix = trim(self::$currentPrefix . '/' . trim($prefix, '/'), '/');
        return new self();
    }

    public function group(callable $routes): void
    {
        $parentPrefix = self::$currentPrefix;
        self::$currentPrefix = $parentPrefix;
        $routes();
        self::$currentPrefix = $parentPrefix;
    }

    protected static function applyPrefix(string $uri): string
    {
        return self::$currentPrefix ? self::$currentPrefix . '/' . trim($uri, '/') : trim($uri, '/');
    }
}