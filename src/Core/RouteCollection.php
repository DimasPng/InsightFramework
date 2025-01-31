<?php

namespace App\Core;

class RouteCollection
{
    private array $routes = [];
    private array $currentGroupAttributes = [];

    public function add(Route $route): void
    {
        $this->applyGroupAttributes($route);
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

    private function applyGroupAttributes(Route $route): void
    {
        $this->applyPrefix($route);
        $this->applyMiddleware($route);
        $this->applyName($route);
    }

    private function applyPrefix(Route $route): void
    {
        if (!empty($this->currentGroupAttributes['prefix'])) {
            $route->uri = trim($this->currentGroupAttributes['prefix'], '/') . '/' . trim($route->uri, '/');
        }
    }

    private function applyMiddleware(Route $route): void
    {
        if (!empty($this->currentGroupAttributes['middleware'])) {
            $route->middleware($this->currentGroupAttributes['middleware']);
        }
    }

    private function applyName(Route $route): void
    {
        if (!empty($this->currentGroupAttributes['name'])) {
            $route->name($this->currentGroupAttributes['name'] . '.' . $route->name ?? '');
        }
    }
}
