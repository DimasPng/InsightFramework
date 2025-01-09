<?php

use App\Controllers\HomeController;
use App\Core\AuthMiddleware;
use App\Core\RouteFacade as Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(AuthMiddleware::class);