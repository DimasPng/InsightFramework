<?php

namespace App\Core;

class RouteCollection
{
    protected array $routes = [];
    protected array $currentGroupAttributes = [];

    public function add(Route $route): void
    {
        if (!empty($this->currentGroupAttributes['prefix'])) {
            $route->uri = trim($this->currentGroupAttributes['prefix'], '/') . '/' . trim($route->uri, '/');
        }

        if (!empty($this->currentGroupAttributes['middleware'])) {
            $middleware = $this->currentGroupAttributes['middleware'];
            $route->middleware($middleware);
        }

        if (!empty($this->currentGroupAttributes['name'])) {
            $route->name($this->currentGroupAttributes['name'] . '.' . $route->name ?? '');
        }

        $this->routes[] = $route;
    }

    public function group(array $attributes, callable $routes): void
    {
        $parentAttributes = $this->currentGroupAttributes;
        $this->currentGroupAttributes = array_merge($parentAttributes, $attributes);

        $routes();

        $this->currentGroupAttributes = $parentAttributes;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
