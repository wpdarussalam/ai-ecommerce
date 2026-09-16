<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class MidtransCallbackController extends Controller
{
    public function callback(Request $request)
    {
        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');

        if (!$orderId) {
            return response()->json(['message' => 'Order ID tidak ditemukan dalam request'], 400);
        }

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan di database'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->update(['status' => 'paid']);
        } elseif ($transactionStatus == 'pending') {
            $order->update(['status' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update(['status' => 'failed']);
        }

        return response()->json(['status' => 'success', 'message' => 'Callback berhasil diproses']);
    }
}