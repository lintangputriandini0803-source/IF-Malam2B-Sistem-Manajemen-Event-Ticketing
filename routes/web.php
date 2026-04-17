<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListBarangController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutUsLinnController;
use App\Http\Controllers\RegisterController;

Route::get('/detail_event', function () {
    return view('detailEvent');
});
Route::get('/payment', function () {
    return view('checkout');
});


Route::get('/about', [AboutUsLinnController::class, 'tampilkan']);
Route::get('/event', [EventController::class, 'index']);
Route::get('/', [HomeController::class, 'index']);

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

Route::get('/register', function () {
    return view('register');
});
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
