<?php

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function add(string $route, string $action): void
    {
        $this->routes[$route] = $action;
    }

    public function dispatch(Request $request): void
    {
        $uri = $request->uri();

        if(!isset($this->routes[$uri])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        [$controller, $method] = explode('@', $this->routes[$uri]);

        $controllerClass = "App\\Controllers\\$controller";
        if(!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Controller $controller not found";
            return;
        }

        $controllerInstance = new $controllerClass();
        if (!method_exists($controllerInstance, $method)) {
            http_response_code(404);
            echo "Method $method not found in $controller";
            return;
        }

        $controllerInstance->$method();
    }
}
