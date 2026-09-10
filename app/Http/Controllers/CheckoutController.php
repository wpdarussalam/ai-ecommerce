<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('frontend.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'address'        => 'required|string',
        ]);

        $cart = session()->get('cart', []);

        if (count($cart) == 0) {
            return redirect()->route('home');
        }

        // Hitung Total Belanja
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Cari atau buat data Customer
        $customer = Customer::firstOrCreate(
            ['phone' => $request->customer_phone],
            [
                'name'    => $request->customer_name,
                'email'   => $request->email,
                'address' => $request->address,
            ]
        );

        $customer->update(['address' => $request->address]);

        // Simpan Data Order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'customer_id'  => $customer->id,
            'total_amount' => $total,
            'status'       => 'pending',
        ]);

        // Simpan Detail Item Order
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal'   => $item['price'] * $item['quantity'],
            ]);
        }

        // Kosongkan Keranjang Belanja
        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id);
    }

    public function success($id)
    {
        $order = Order::with('customer')->findOrFail($id);
        return view('frontend.success', compact('order'));
    }
}