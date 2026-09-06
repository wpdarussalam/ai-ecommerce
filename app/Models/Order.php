<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingRate()
    {
        return $this->belongsTo(ShippingRate::class);
    }

    /**
     * Logika Otomatisasi Stok Produk
     */
    protected static function booted(): void
    {
        // Potong stok saat pesanan baru dibuat
        static::created(function (Order $order) {
            self::reduceStock($order);
        });

        // Pengelolaan stok saat status pesanan diubah
        static::updated(function (Order $order) {
            if ($order->isDirty('status') && $order->status === 'cancelled') {
                self::restoreStock($order);
            }

            if ($order->isDirty('status') && $order->getOriginal('status') === 'cancelled' && $order->status !== 'cancelled') {
                self::reduceStock($order);
            }
        });

        // Kembalikan stok jika pesanan dihapus
        static::deleted(function (Order $order) {
            if ($order->status !== 'cancelled') {
                self::restoreStock($order);
            }
        });
    }

    public static function reduceStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
            }
        }
    }

    public static function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }
    }
}