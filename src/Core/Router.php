<?php

namespace App\Core;

use App\Core\Middleware\MiddlewarePipeline;
use Exception;
use ReflectionException;

class Router
{
    protected RouteCollection $routeCollection;

    public function __construct()
    {
        $this->routeCollection = new RouteCollection();
    }

    public function add(string $method, string $uri, array|string $action): Route
    {
        $route = new Route ($uri, $method, $action);
        $this->routeCollection->add($route);
        return $route;
    }

    public function group(array $attributes, callable $routes): void
    {
        $this->routeCollection->group($attributes, $routes);
    }

    /**
     * @throws ReflectionException
     */
    public function dispatch(Request $request, Container $container): void
    {
        $uri = trim($request->uri(), '/');
        $method = $request->method();

        $routeFound = false;

        foreach ($this->routeCollection->getRoutes() as $route) {
            $definition = $route->getDefinition();
            $registeredUri = trim($definition['uri'], '/');

            if ($registeredUri === $uri && $definition['method'] === $method) {
                $routeFound = true;

                $middlewarePipeline = new MiddlewarePipeline();

                foreach ($definition['middleware'] as $middlewareClass) {
                    $middlewarePipeline->pipe($container->make($middlewareClass));
                }

                $response = $middlewarePipeline->process($request, function ($request) use ($container, $definition) {
                    $action = $definition['action'];

                    if (is_array($action)) {
                        [$controller, $method] = $action;
                        $controllerInstance = $container->make($controller);
                        return $controllerInstance->$method();
                    }

                    if (is_callable($action)) {
                        return call_user_func($action);
                    }

                    throw new Exception("Invalid route action");
                });

                echo $response;
                return;
            }
        }

        if (!$routeFound) {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
