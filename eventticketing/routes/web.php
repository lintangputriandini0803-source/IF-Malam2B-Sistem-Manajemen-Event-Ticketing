<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListItemController;

//Route::get('/', function () {
//    return view('welcome');
Route::get('/login', [LoginController::class,'index']);
Route::post('/login', [LoginController::class, 'login']);
// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

// List Item (dengan parameter opsional)
Route::get('/listitem/{id?}/{name?}', [ListItemController::class, 'show']);

Route::get('/app', function () {
    return view('app');
});
Route::get('/app', function () {
    return view('app');
});