<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\Product;

class OrderItemObserver
{
    public function created(OrderItem $orderItem): void
    {
        if ($orderItem->product) {
            Product::where('id', $orderItem->product_id)->decrement('stock', $orderItem->quantity);
        }
    }

    public function deleted(OrderItem $orderItem): void
    {
        if ($orderItem->product) {
            Product::where('id', $orderItem->product_id)->increment('stock', $orderItem->quantity);
        }
    }
}