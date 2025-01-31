<?php

namespace App\Core\Middleware;

use App\Core\Request;
use App\Http\Response\Response;

interface Middleware
{
    public function handle(Request $request, callable $next): Response;
}