<?php

namespace App\Core;

use Exception;

class Router
{
    protected array $routes = [];

    public function add(string $method, string $uri, array|string $action): void
    {
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch(Request $request, Container $container): void
    {
        $uri = $request->uri();
        $method = $request->method();

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $action = $this->routes[$method][$uri];

        try {
            if (is_array($action)) {
                [$controllerKey, $method] = $action;
                $controller = $container->make($controllerKey);

                if (!method_exists($controller, $method)) {
                    throw new Exception("Method $method not found in $controllerKey");
                }

                $controller->$method();
            } elseif (is_string($action) && is_callable($action)) {
                call_user_func($action);
            } else {
                throw new Exception("Invalid route action for $uri");
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }
}
