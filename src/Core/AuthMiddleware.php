<?php

namespace App\Core;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, callable $next): mixed
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            return "Forbidden: Unauthorized access.";
        }

        return $next($request);
    }
}