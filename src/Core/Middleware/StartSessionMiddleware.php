<?php

namespace App\Core\Middleware;

use App\Core\Request;
use App\Http\Response\Response;

class StartSessionMiddleware implements Middleware
{
    public function handle(Request $request, callable $next): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        return $next($request);
    }
}
