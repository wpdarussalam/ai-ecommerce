<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel categories
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete()
                ->index();

            // Informasi Dasar Produk
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();



            // Harga & Stok
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('old_price', 10, 2)->nullable();

            //Iventaris
            $table->string('sku')->unique()->nullable();
            $table->integer('stock')->default(0);

            // Media & Status
            //Product Image
            $table->string('image')->nullable();

            //Product Visibility
            $table->boolean('featured')->default(0);
            $table->boolean('status')->default(1);
            //$table->boolean('is_active')->default(true);

            //Analytics(future AI usage)
            $table->integer('views')->default(0);
            $table->integer('sales_count')->default(0);

            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
