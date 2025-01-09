<?php

use App\Controllers\HomeController;
use App\Core\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);