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
        $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
        $table->foreignId('shipping_rate_id')->nullable()->constrained()->nullOnDelete();
        $table->string('order_number')->unique();
        $table->string('status')->default('pending');
        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('shipping_cost', 12, 2)->default(0);
        $table->decimal('total_amount', 12, 2)->default(0);
        $table->text('shipping_address')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
