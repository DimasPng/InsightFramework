<?php

namespace App\Core\Middleware;

use App\Core\Request;

class StartSessionMiddleware implements Middleware
{
    public function handle(Request $request, callable $next): mixed
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        return $next($request);
    }
}
