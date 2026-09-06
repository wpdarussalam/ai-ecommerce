<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   public function print(Order $order)
{
    $order->load(['customer', 'items.product', 'shippingRate']);

    return view('orders.print', compact('order'));
}
}