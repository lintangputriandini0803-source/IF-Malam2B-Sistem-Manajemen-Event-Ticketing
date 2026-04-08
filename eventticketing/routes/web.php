<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListItemController;

// Login
Route::get('/login', [LoginController::class, 'showLogin']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

// List Item (dengan parameter opsional)
Route::get('/listitem/{id?}/{name?}', [ListItemController::class, 'show']);
