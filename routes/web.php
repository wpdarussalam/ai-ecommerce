<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\MidtransCallbackController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aura Commerce
|--------------------------------------------------------------------------
*/

// --- TOKO DEPAN (FRONTEND) ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// --- KERANJANG BELANJA (CART) ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// --- CHECKOUT & TRANSAKSI ---
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order_number}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/pay/{order_number}', [CheckoutController::class, 'pay'])->name('checkout.pay');


// --- ADMIN PANEL ACTIONS ---
Route::get('/admin/orders/{id}/print', [InvoiceController::class, 'print'])->name('admin.orders.print');

Route::post('/midtrans-callback', [MidtransCallbackController::class, 'callback']);

