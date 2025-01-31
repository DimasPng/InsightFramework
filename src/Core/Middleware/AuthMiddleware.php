<?php

namespace App\Core\Middleware;

use App\Core\Auth\AuthManager;
use App\Core\Request;
use App\Http\Response\Response;

class AuthMiddleware implements Middleware
{
    public function __construct(
        protected AuthManager $auth
    )
    {
    }

    public function handle(Request $request, callable $next): Response
    {
        if (!$this->auth->check()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}