<?php

namespace App\Core\Middleware;

use App\Core\Request;

class MiddlewarePipeline
{
    protected array $middleware = [];

    public function pipe(Middleware $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function process(Request $request, callable $coreHandler): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->middleware),
            fn($next, Middleware $middleware) => fn(Request $request) => $middleware->handle($request, $next),
            $coreHandler
        );

        return $pipeline($request);
    }

}