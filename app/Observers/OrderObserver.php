<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Product;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            if ($order->status === 'cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }

            if ($order->getOriginal('status') === 'cancelled' && $order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
                }
            }
        }
    }

    public function deleted(Order $order): void
    {
        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)->increment('stock', $item->quantity);
        }
    }
}