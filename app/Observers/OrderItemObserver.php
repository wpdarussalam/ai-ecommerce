<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\Product;

class OrderItemObserver
{
    /**
     * Dijalankan saat item pesanan baru dibuat/disimpan di DB
     */
    public function created(OrderItem $orderItem): void
    {
        // Kurangi stok produk sesuai quantity
        $product = Product::find($orderItem->product_id);
        if ($product) {
            $product->decrement('stock', $orderItem->quantity);
        }
    }

    /**
     * Dijalankan saat item pesanan dihapus
     */
    public function deleted(OrderItem $orderItem): void
    {
        // Kembalikan stok jika item dihapus
        $product = Product::find($orderItem->product_id);
        if ($product) {
            $product->increment('stock', $orderItem->quantity);
        }
    }
}