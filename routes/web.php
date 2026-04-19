<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\CheckoutController;

// ─── Public ───────────────────────────────────────────────────────────────────

Route::get('/', [EventController::class, 'index'])->name('home');

// Detail event — pakai slug agar URL lebih bersih, misal: /event/pagelaran-teknovasi
Route::get('/event/{event:slug}', [EventController::class, 'show'])->name('event.show');


Route::post('/event/{event:slug}/daftar', [RegistrationController::class, 'store'])->name('registration.store');
Route::get('/registrasi/{regNumber}/sukses', [RegistrationController::class, 'success'])->name('registration.success');
Route::post('/event/{event:slug}/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');


// ─── Auth ─────────────────────────────────────────────────────────────────────

Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ─── Admin ────────────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// ─── Panitia ──────────────────────────────────────────────────────────────────

Route::prefix('panitia')->name('panitia.')->middleware(['auth', 'panitia'])->group(function () {
    Route::get('/dashboard', function () {
        return view('panitia.dashboard');
    })->name('dashboard');
});

