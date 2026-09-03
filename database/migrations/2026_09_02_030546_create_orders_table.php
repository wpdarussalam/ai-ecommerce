<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // contoh: ORD-20260902-0001

            // Data Pelanggan
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');

            // Pembayaran & Total
            $table->decimal('grand_total', 12, 2);
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed
            $table->string('payment_method')->nullable(); // bank_transfer, qris, cod

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
