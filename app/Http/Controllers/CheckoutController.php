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

   public function pay($order_number)
{
    $order = Order::with('customer')->where('order_number', $order_number)->firstOrFail();

    if (is_null($order->snap_token)) {
        // Konfigurasi Midtrans SDK
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Parameter Transaksi
        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer->name ?? 'Customer',
                'email'      => $order->customer->email ?? 'email@example.com',
                'phone'      => $order->customer->phone ?? '08123456789',
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snap_token = $snapToken;
            $order->save();
        } catch (\Exception $e) {
            // Tampilkan error secara langsung di layar agar ketahuan penyebabnya
            dd('Gagal Generate Snap Token:', $e->getMessage(), $params);
        }
    }

    return view('frontend.pay', compact('order'));
}
   public function store(Request $request)
{
    // 1. Ambil data keranjang dari session
    $cart = session()->get('cart', []);

    // Jika keranjang kosong, batalkan checkout
    if (empty($cart)) {
        return redirect()->route('home')->with('error', 'Keranjang kamu masih kosong!');
    }

    $request->validate([
        'customer_name'  => 'required|string|max:255',
        'email'          => 'required|email|max:255',
        'customer_phone' => 'required|string|max:20',
        'address'        => 'required|string',
    ]);

    // 2. Hitung Total Belanja
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Pastikan total tidak 0
    if ($total <= 0) {
        return back()->with('error', 'Total pembayaran harus lebih besar dari 0!');
    }

    // 3. Simpan Customer
    $customer = Customer::firstOrCreate(
        ['email' => $request->email],
        [
            'name'    => $request->customer_name,
            'phone'   => $request->customer_phone,
            'address' => $request->address,
        ]
    );

    $customer->update([
        'name'    => $request->customer_name,
        'phone'   => $request->customer_phone,
        'address' => $request->address,
    ]);

    // 4. Simpan Order
    $order = Order::create([
        'order_number' => 'ORD-' . strtoupper(uniqid()),
        'customer_id'  => $customer->id,
        'total_amount' => $total,
        'status'       => 'pending',
    ]);

    // 5. Simpan Order Items
    foreach ($cart as $productId => $item) {
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $productId,
            'quantity'   => $item['quantity'],
            'unit_price' => $item['price'],
            'subtotal'   => $item['price'] * $item['quantity'],
        ]);
    }

    // 6. Hapus keranjang
    session()->forget('cart');

    // 7. Redirect ke halaman bayar
    return redirect()->route('checkout.pay', $order->order_number);
}
    public function success($order_number)
    {
        $order = Order::with('customer')->where('order_number', $order_number)->firstOrFail();
        return view('frontend.success', compact('order'));     
    }
}