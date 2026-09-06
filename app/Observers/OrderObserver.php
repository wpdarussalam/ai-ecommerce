<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Product;

class OrderObserver
{
    /**
     * Menangani pengembalian stok saat status order diubah ke Cancelled
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            // Jika diubah jadi Cancelled -> kembalikan stok
            if ($order->status === 'cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }

            // Jika dari Cancelled diubah kembali ke status lain -> kurangi stok lagi
            if ($order->getOriginal('status') === 'cancelled' && $order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
                }
            }
        }
    }
}