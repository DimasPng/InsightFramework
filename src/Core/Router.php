<?php

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function add(string $route, string $action): void
    {
        $this->routes[$route] = $action;
    }

    public function dispatch(Request $request, Container $container): void
    {
        $uri = $request->uri();

        if(!isset($this->routes[$uri])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        [$controllerKey, $method] = explode('@', $this->routes[$uri]);

        try {
            $controller = $container->make($controllerKey);

            if (!method_exists($controller, $method)) {
                throw new \Exception("Method $method not found in $controllerKey");
            }

            $controller->$method();
        } catch (\Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }
}
