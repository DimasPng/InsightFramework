<?php

use App\Core\Middleware\AuthMiddleware;
use App\Core\RouteFacade as Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(AuthMiddleware::class);