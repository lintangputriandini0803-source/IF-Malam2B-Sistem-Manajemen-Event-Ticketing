<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Panitia\EventController as PanitiaEventController;
use App\Http\Controllers\Panitia\TicketTypeController;
use App\Http\Controllers\Panitia\ParticipantController;

// ─── Public ───────────────────────────────────────────────────────────────────

Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/event', [EventController::class, 'event'])->name('homepage');
Route::get('/event/{event:slug}', [EventController::class, 'show'])->name('event.show');
Route::post('/event/{event:slug}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// ─── Auth ─────────────────────────────────────────────────────────────────────

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Akses /login langsung → redirect ke home, modal terbuka otomatis
Route::get('/login', function () {
    return redirect('/')->with('open_login_modal', true);
})->name('login');

// ─── Admin ────────────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // User management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::patch('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');

    // Categories
    Route::resource('/categories', CategoryController::class);
});

// ─── Panitia ──────────────────────────────────────────────────────────────────

Route::prefix('panitia')->name('panitia.')->middleware(['auth', 'panitia'])->group(function () {
    Route::get('/dashboard', function () {
        return view('panitia.dashboard');
    })->name('dashboard');

    // Events CRUD
    Route::get('/events', [PanitiaEventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [PanitiaEventController::class, 'create'])->name('events.create');
    Route::post('/events', [PanitiaEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [PanitiaEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [PanitiaEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [PanitiaEventController::class, 'destroy'])->name('events.destroy');
    Route::patch('/events/{event}/publish', [PanitiaEventController::class, 'publish'])->name('events.publish');

    // Tickets & Participants
    Route::resource('/events/{event}/tickets', TicketTypeController::class)->names('tickets');
    Route::get('/events/{event}/participants', [ParticipantController::class, 'index'])->name('participants');
});
