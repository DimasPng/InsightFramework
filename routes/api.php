<?php

use App\Core\RouteFacade as Route;
use App\Http\Controllers\HomeController;
use dimaspng\oauth2auth\Core\Middleware\ApiAuthMiddleware;
use dimaspng\oauth2auth\Http\OAuthController;

Route::post('/api/get-token', [OAuthController::class, 'token']);
Route::get('/api/get-data', [HomeController::class, 'about'])->middleware(ApiAuthMiddleware::class);
