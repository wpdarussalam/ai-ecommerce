<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'grand_total',
        'status',
        'payment_status',
        'payment_method',
        'notes',
    ];

    /**
     * Relasi: Satu pesanan memiliki banyak item produk
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
