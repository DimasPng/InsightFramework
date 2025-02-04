<?php

namespace App\Core;

use App\Core\Middleware\MiddlewareStack;
use Exception;

class Router
{
    public function __construct(
        protected RouteCollection $routeCollection,
        protected MiddlewareStack $middlewareStack,
    )
    {
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

    public function dispatch(Request $request, Container $container): void
    {
        $route = $this->findRoute($request);

        if (!$route) {
            $this->sendNotFoundResponse();
            exit;
        }

        $middleware = $this->resolveMiddlewareRoute($route);

        $response = $this->middlewareStack->handle(
            $request,
            fn () => $this->executeRoute($route, $container),
            $middleware
        );

        $response->send();
    }

    private function findRoute(Request $request): ?Route
    {
        foreach ($this->routeCollection->getRoutes() as $route) {
            $definition = $route->getDefinition();

            if (trim($definition['uri'], '/') === trim($request->uri(), '/') &&
                $definition['method'] === $request->method()) {
                return $route;
            }
        }

        return null;
    }

    private function resolveMiddlewareRoute(Route $route): array
    {
        $routeMiddleware = $route->getDefinition()['middleware'];
        $expandedMiddleware = [];

        foreach ($routeMiddleware as $mw) {
            if (isset($this->middlewareStack->getGroupMiddleware()[$mw])) {
                $expandedMiddleware = array_merge(
                    $expandedMiddleware,
                    $this->middlewareStack->getGroupMiddleware()[$mw]
                );
            } else {
                $expandedMiddleware[] = $mw;
            }
        }

        return $expandedMiddleware;
    }

    private function executeRoute(Route $route, Container $container): mixed
    {
        $action = $route->getDefinition()['action'];

        if (is_array($action) || is_callable($action)) {
            return $container->call($action);
        }

        throw new Exception('Invalid route action');
    }

    private function sendNotFoundResponse(): void
    {
        http_response_code(404);
        echo "404 Not Found";
    }
}
