<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Models\Product;

// ==============================
// 🔹 Halaman Utama
// ==============================
Route::get('/', function () {
    $products = Product::all();
    return view('welcome', compact('products'));
})->name('welcome');

// ==============================
// 🔹 Authentication
// ==============================
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ==============================
// 🔹 Produk (akses login saja)
// ==============================
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);

    // 🔸 Riwayat Transaksi (Admin)
    Route::get('/transactions', [CheckoutController::class, 'history'])->name('transactions.history');
});

// ==============================
// 🔹 Keranjang Belanja
// ==============================
Route::post('/cart/{id}', function ($id, Request $request) {
    $product = Product::findOrFail($id);
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            "name" => $product->name,
            "price" => $product->price,
            "image" => $product->image,
            "quantity" => 1
        ];
    }

    session()->put('cart', $cart);

    return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
})->name('cart.add');

Route::get('/cart', function () {
    $cart = session()->get('cart', []);
    return view('cart.index', compact('cart'));
})->name('cart.index');

Route::delete('/cart/{id}', function ($id) {
    $cart = session()->get('cart', []);
    if (isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);
    }

    return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang!');
})->name('cart.remove');

// ==============================
// 🔹 Checkout
// ==============================
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// ==============================
// 🔹 Halaman Admin: Pesanan
// ==============================
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/orders', [CheckoutController::class, 'adminOrders'])->name('admin.orders');
    Route::patch('/admin/orders/{transaction}/status', [App\Http\Controllers\AdminController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::patch('/admin/orders/{id}/status', [CheckoutController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/admin/orders/{id}/invoice', [CheckoutController::class, 'downloadInvoice'])->name('admin.orders.invoice');
});

// ==============================
// 🔹 Simulasi Pembayaran (✅ GET bukan POST)
// ==============================
Route::get('/payment/{transactionId}', [CheckoutController::class, 'simulatePayment'])
    ->name('payment.simulate')
    ->middleware('auth');

// ==============================
// 🔹 Halaman Riwayat Transaksi untuk Pelanggan
// ==============================
Route::get('/my-transactions', [CheckoutController::class, 'userHistory'])
    ->name('transactions.userHistory')
    ->middleware('auth');

// ==============================
// 🔹 Unduh & Konfirmasi Invoice (untuk pelanggan)
// ==============================
Route::middleware('auth')->group(function () {
    Route::get('/invoice/{transaction}/download', [InvoiceController::class, 'download'])->name('invoice.download');
    Route::get('/invoice/{transaction}/confirm', [InvoiceController::class, 'confirm'])->name('invoice.confirm');
});

// ==============================
// 🔹 Konfirmasi Pembayaran oleh Pelanggan
// ==============================
Route::middleware('auth')->group(function () {
    // Menampilkan instruksi pembayaran
    Route::get('/transactions/{transaction}/payment', [TransactionController::class, 'showPaymentInstructions'])->name('transactions.payment');

    // Aksi saat user klik "Saya Sudah Bayar"
    Route::post('/transactions/{transaction}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('transactions.confirmPayment');
});

// ==============================
// 🔹 Pengaturan Akun (Pelanggan)
// ==============================
Route::middleware('auth')->group(function () {
    Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/settings', [AccountController::class, 'update'])->name('account.update');
});

// ==============================
// 🔹 Payment Routes
// ==============================
Route::middleware('auth')->group(function () {
    // 🔹 Info Pembayaran
    Route::get('/payment-info/{transaction}', [PaymentController::class, 'show'])->name('payment.info');

    // 🔹 Form Upload Bukti Pembayaran
    Route::get('/payment-proof/{transaction}', [PaymentController::class, 'uploadForm'])->name('payment.proof.form');

    // 🔹 Upload Bukti Pembayaran
    Route::post('/payment-proof/{transaction}', [PaymentController::class, 'upload'])->name('payment.proof.upload');
});

// ==============================
// 🔹 Upload Bukti Transfer oleh Pelanggan
// ==============================
Route::middleware('auth')->group(function () {
    // Menampilkan form upload bukti transfer
    Route::get('/transactions/{transaction}/upload-bukti', [TransactionController::class, 'showUploadBukti'])->name('transactions.upload-bukti');

    // Aksi untuk mengunggah bukti transfer
    Route::post('/transactions/{transaction}/upload-bukti', [TransactionController::class, 'uploadBukti'])->name('transactions.upload-bukti.store');
});

