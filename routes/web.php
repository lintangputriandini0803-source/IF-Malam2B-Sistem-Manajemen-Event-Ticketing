<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController as AdminSettings;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Panitia\EventController as PanitiaEventController;
use App\Http\Controllers\Panitia\TicketTypeController;
use App\Http\Controllers\Panitia\ParticipantController;
use App\Http\Controllers\AboutUsController;

// ─── Public ───────────────────────────────────────────────────────────────────

Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/event', [EventController::class, 'event'])->name('homepage');
Route::get('/event/{event:slug}', [EventController::class, 'show'])->name('event.show');
Route::get('/about', [AboutUsController::class, 'tampilkan'])->name('about');

// ─── Checkout (multi-step) ────────────────────────────────────────────────────

// Step 1: Terima pilihan tiket → tampil form checkout
Route::post('/event/{event:slug}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Step 2: Proses form checkout → simpan ke DB → redirect ke payment
Route::post('/event/{event:slug}/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

// Step 3: Halaman tunggu pembayaran (VA)
Route::get('/event/{event:slug}/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');

// Step 4: Ringkasan tiket
Route::get('/event/{event:slug}/summary', [CheckoutController::class, 'summary'])->name('checkout.summary');

// ─── Auth ─────────────────────────────────────────────────────────────────────

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

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

    // Event management
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::patch('/events/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
    Route::patch('/events/{event}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
    Route::delete('/events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');

    // Transactions
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');

    // Settings
    Route::get('/settings', [AdminSettings::class, 'index'])->name('settings');
    Route::put('/settings/update', [AdminSettings::class, 'update'])->name('settings.update');
    Route::put('/settings/profile', [AdminSettings::class, 'profile'])->name('settings.profile');
    Route::put('/settings/password', [AdminSettings::class, 'password'])->name('settings.password');

    // Categories
    Route::resource('/categories', CategoryController::class);
});

// ─── Panitia ──────────────────────────────────────────────────────────────────

Route::prefix('panitia')->name('panitia.')->middleware(['auth', 'panitia'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Panitia\DashboardController::class, 'index'])->name('dashboard');

    // Settings
    Route::get('/settings', function () {
        return view('panitia.settings');
    })->name('settings');
    Route::post('/settings/update', function () {
        return back()->with('success', 'Profil berhasil diperbarui.');
    })->name('settings.update');
    Route::post('/settings/password', function () {
        return back()->with('success', 'Password berhasil diubah.');
    })->name('settings.password');
    Route::post('/settings/notifications', function () {
        return back()->with('success', 'Pengaturan notifikasi disimpan.');
    })->name('settings.notifications');

    // Report peserta
    Route::get('/report-peserta', [ParticipantController::class, 'report'])->name('report-peserta');

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
