<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListBarangController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AboutUsLinnController;

Route::get('/about', [AboutUsLinnController::class, 'tampilkan']);
Route::get('/event', [EventController::class, 'index']);
Route::get('/', function () {
    return view('app');
});

Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/user/{id}', function ($id) {
    return 'User dengan ID ' . $id;
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return 'Admin Dashboard';
    });

    Route::get('/users', function () {
        return 'Admin Users';
    });
});

// Route::get('/listbarang/{id}/{nama}', function($id, $nama) {
//    return view('list_barang', compact('id', 'nama'));
//});

Route::get('/barang', [ListBarangController::class, 'index']);