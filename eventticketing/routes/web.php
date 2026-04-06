<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

//Route::get('/', function () {
//    return view('welcome');
Route::get('/login', [LoginController::class,'index']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/dashboard', function () {
    return "Berhasil Login, ini Dashboard";
});
