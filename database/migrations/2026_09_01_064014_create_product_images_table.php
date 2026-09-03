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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel products
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Path/URL file gambar
            $table->string('image');

            // Urutan tampilan gambar di galeri
            $table->integer('position')->default(0);

            // Penanda gambar utama / thumbnail
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
