<?php

namespace App\Http;

use App\Core\Middleware\MiddlewareStack;

class Kernel
{
    public function __construct(
        private readonly MiddlewareStack $middlewareStack
    )
    {
    }

    protected array $middleware = [];

    protected array $middlewareGroups = [
        'web' => [
            \App\Core\Middleware\StartSessionMiddleware::class,
        ],
        'api' => [],
    ];

    public function handleMiddleware(): void
    {
        $this->middlewareStack->addGlobal($this->middleware);

        foreach ($this->middlewareGroups as $group => $middlewares) {
            $this->middlewareStack->addGroup($group, $middlewares);
        }
    }
}
