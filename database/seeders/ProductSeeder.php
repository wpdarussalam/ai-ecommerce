<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $androidCategory = Category::where('slug', 'android-phones')->first();
        $electronicsCategory = Category::where('slug', 'electronics')->first();

        if ($androidCategory) {
            Product::updateOrCreate(
                ['slug' => Str::slug('Samsung Galaxy S24 Ultra')],
                [
                    'category_id' => $androidCategory->id,
                    'name' => 'Samsung Galaxy S24 Ultra',
                    'description' => 'Flagship smartphone dari Samsung dengan fitur AI canggih.',
                    'short_description' => 'Smartphone AI Flagship Samsung.',
                    'price' => 21999000,
                    'old_price' => 23999000,
                    'sku' => 'SAMSUNG-S24U-256',
                    'stock' => 50,
                    'image' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=500',
                    'featured' => true,
                    'status' => true,
                    'meta_title' => 'Buy Samsung Galaxy S24 Ultra',
                    'meta_description' => 'Best price for Samsung Galaxy S24 Ultra',
                    'meta_keywords' => 'Samsung, Galaxy S24 Ultra, mobile',
                ]
            );

            Product::updateOrCreate(
                ['slug' => Str::slug('Google Pixel 8 Pro')],
                [
                    'category_id' => $androidCategory->id,
                    'name' => 'Google Pixel 8 Pro',
                    'description' => 'Smartphone dengan kamera komputasional terbaik dari Google.',
                    'short_description' => 'Google Pixel 8 Pro Kamera Komputasional.',
                    'price' => 15500000,
                    'old_price' => 16500000,
                    'sku' => 'PIXEL-8PRO-128',
                    'stock' => 40,
                    'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500',
                    'featured' => false,
                    'status' => true,
                    'meta_title' => 'Buy Google Pixel 8 Pro Online',
                    'meta_description' => 'Best price for Google Pixel 8 Pro',
                    'meta_keywords' => 'Google, Pixel 8 Pro, Smartphone',
                ]
            );
        }

        if ($electronicsCategory) {
            Product::updateOrCreate(
                ['slug' => Str::slug('Smart TV LG 55 Inch 4K')],
                [
                    'category_id' => $electronicsCategory->id,
                    'name' => 'Smart TV LG 55 Inch 4K',
                    'description' => 'Televisi pintar layar lebar beresolusi 4K Ultra HD.',
                    'short_description' => 'LG Smart TV 4K 55 Inch.',
                    'price' => 8500000,
                    'old_price' => null,
                    'sku' => 'LG-TV-55-4K',
                    'stock' => 50,
                    'image' => 'https://images.unsplash.com/photo-1593784991095-a205069470b6?w=500',
                    'featured' => true,
                    'status' => true,
                    'meta_title' => 'Buy Smart TV LG 55 Inch 4K Online',
                    'meta_description' => 'Best price for Smart TV LG 55 Inch 4K',
                    'meta_keywords' => 'TV, LG, Smart TV',
                ]
            );
        }
    }
}