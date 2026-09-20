<?php

use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\KeripikController as AdminKeripikController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\KeripikController;
use App\KategoriKeripik;
use App\Models\Keripik;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $keripiks = Keripik::where('is_active', true)
        ->whereIn('kategori', KategoriKeripik::values())
        ->latest()
        ->get();

    return view('welcome', compact('keripiks'));
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
    Route::get('/register', [AuthenticatedSessionController::class, 'createRegistration'])->name('register');
    Route::post('/register', [AuthenticatedSessionController::class, 'register'])->name('register.store');
});

// Route untuk pengunjung umum melihat detail keripik
Route::get('/keripik/{keripik}', [KeripikController::class, 'show'])->name('keripik.show');

Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{pesanan}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Chat routes for customers (public, no auth required)
Route::get('/chat/messages', [ChatController::class, 'messages'])->name('chat.messages');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::redirect('/', '/admin/keripik');
    Route::resource('keripik', AdminKeripikController::class);
    Route::resource('pesanan', \App\Http\Controllers\Admin\PesananController::class)->except(['create', 'store', 'destroy']);

    // Admin chat
    Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chatSession}', [AdminChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chatSession}/reply', [AdminChatController::class, 'reply'])->name('chat.reply');
});
