<?php

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function add(string $route, string $controller, string $method): void
    {
        $this->routes[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch(string $uri): void
    {
        if (array_key_exists($uri, $this->routes)) {
            $route = $this->routes[$uri];
            $controllerName = "\\App\\Controllers\\" . $route['controller'];
            $method = $route['method'];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $method)) {
                    $controller->$method();
                    return;
                }
            }

            http_response_code(404);
            echo "Method not found";
            return;
        }

        http_response_code(404);
        echo "Route not found";
    }
}
