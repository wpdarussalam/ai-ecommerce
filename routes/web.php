<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');