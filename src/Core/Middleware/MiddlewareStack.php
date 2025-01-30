<?php

namespace App\Core\Middleware;

use App\Core\Request;
use Closure;

class MiddlewareStack
{
    protected array $globalMiddleware = [];
    protected array $groupMiddleware = [];

    public function addGlobal(array $middleware): void
    {
        $this->globalMiddleware = array_merge($this->globalMiddleware, $middleware);
    }

    public function addGroup(string $group, array $middleware): void
    {
        $this->groupMiddleware[$group] = $middleware;
    }

    public function handle(Request $request, Closure $next, ?array $routeMiddleware = []): mixed
    {
        $middleware = $this->createMiddlewareInstances(array_merge($this->globalMiddleware, $routeMiddleware));

        $pipeline = array_reduce(
            array_reverse($middleware),
            fn($next, Middleware $middleware) => fn(Request $request) => $middleware->handle($request, $next),
            $next
        );

        return $pipeline($request);
    }

    public function getGroupMiddleware(): array
    {
        return $this->groupMiddleware;
    }

    private function createMiddlewareInstances(array $middlewareClasses): array
    {
        return array_map(fn($mw) => is_string($mw) ? app()->getContainer()->make($mw) : $mw, $middlewareClasses);
    }
}