<?php

use App\Enums\KategoriKeripik;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\KeripikController as AdminKeripikController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\KeripikController;
use App\Http\Controllers\PesananSayaController;
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

// Chat routes for customers (public, no auth required)
Route::get('/chat/messages', [ChatController::class, 'messages'])->name('chat.messages');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

// Akses yang Membutuhkan Login (Checkout, Pesanan, & Logout)
Route::middleware('auth')->group(function (): void {
    // Checkout Routes
    Route::get('/checkout/cart', [CheckoutController::class, 'cart'])->name('checkout.cart');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{pesanan}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Pesanan Saya Route (Pengganti Cek Pesanan)
    Route::get('/pesanan-saya', [PesananSayaController::class, 'index'])->name('orders.index');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::redirect('/', '/admin/keripik');
    Route::resource('keripik', AdminKeripikController::class);
    Route::resource('pesanan', PesananController::class)->except(['create', 'store', 'destroy']);

    // Admin chat
    Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chatSession}', [AdminChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chatSession}/reply', [AdminChatController::class, 'reply'])->name('chat.reply');
    Route::delete('/chat/{chatSession}', [AdminChatController::class, 'destroy'])->name('chat.destroy');
});
