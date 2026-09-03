<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // <-- Import Str

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $androidCategory = Category::where('slug', 'android-phones')->first();
        $electronicsCategory = Category::where('slug', 'electronics')->first();

        if ($androidCategory) {
            Product::create([
                'category_id' => $androidCategory->id,
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => Str::slug('Samsung Galaxy S24 Ultra'), // <-- Tambahkan ini
                'description' => 'Flagship smartphone dari Samsung dengan fitur AI canggih.',
                'short_description' => 'Smartphone AI Flagship Samsung.',
                'price' => 21999000,
                'old_price' => 23999000,
                'sku' => 'SAMSUNG-S24U-256',
                'stock' => 50,
                'featured' => true,
                'status' => true,
                'meta_title' => 'Buy Samsung Galaxy S24 Ultra',
                'meta_description' => 'Best price for Samsung Galaxy S24 Ultra',
                'meta_keywords' => 'Samsung, Galaxy S24 Ultra, mobile',
            ]);

            Product::create([
                'category_id' => $androidCategory->id,
                'name' => 'Google Pixel 8 Pro',
                'slug' => Str::slug('Google Pixel 8 Pro'), // <-- Tambahkan ini
                'description' => 'Smartphone dengan kamera komputasional terbaik dari Google.',
                'short_description' => 'Google Pixel 8 Pro Kamera Komputasional.',
                'price' => 15500000,
                'old_price' => 16500000,
                'sku' => 'PIXEL-8PRO-128',
                'stock' => 40,
                'featured' => false,
                'status' => true,
                'meta_title' => 'Buy Google Pixel 8 Pro Online',
                'meta_description' => 'Best price for Google Pixel 8 Pro',
                'meta_keywords' => 'Google, Pixel 8 Pro, Smartphone',
            ]);
        }

        if ($electronicsCategory) {
            Product::create([
                'category_id' => $electronicsCategory->id,
                'name' => 'Smart TV LG 55 Inch 4K',
                'slug' => Str::slug('Smart TV LG 55 Inch 4K'), // <-- Tambahkan ini
                'description' => 'Televisi pintar layar lebar beresolusi 4K Ultra HD.',
                'short_description' => 'LG Smart TV 4K 55 Inch.',
                'price' => 8500000,
                'old_price' => null,
                'sku' => 'LG-TV-55-4K',
                'stock' => 50,
                'featured' => true,
                'status' => true,
                'meta_title' => 'Buy Smart TV LG 55 Inch 4K Online',
                'meta_description' => 'Best price for Smart TV LG 55 Inch 4K',
                'meta_keywords' => 'TV, LG, Smart TV',
            ]);
        }
    }
}
