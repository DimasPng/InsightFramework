<?php

namespace App\Core\Middleware;

use App\Core\Auth\AuthManager;
use App\Core\Request;

class AuthMiddleware implements Middleware
{
    public function __construct(
        protected AuthManager $auth
    )
    {
    }

    public function handle(Request $request, callable $next): mixed
    {
        if (!$this->auth->check()) {
            http_response_code(403);
            return 'Forbidden. You are not authenticated.';
        }

        return $next($request);
    }
}