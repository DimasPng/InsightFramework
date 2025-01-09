<?php

namespace App\Core\Middleware;

use App\Core\Request;

interface Middleware
{
    public function handle(Request $request, callable $next): mixed;
}