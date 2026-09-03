<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create([
            'name'     => 'Electronics',
            'slug'     => 'electronics',
            'position' => 1,
            'level'    => 1,
        ]);

        $mobiles = $electronics->children()->create([
            'name'     => 'Mobile Phones',
            'slug'     => 'mobile-phones',
            'position' => 1,
            'level'    => 2,
        ]);

        $mobiles->children()->create([
            'name'     => 'Android Phones',
            'slug'     => 'android-phones',
            'position' => 1,
            'level'    => 3,
        ]);

        Category::create([
            'name'     => 'Fashion',
            'slug'     => 'fashion',
            'position' => 2,
            'level'    => 1,
        ]);

        Category::create([
            'name'     => 'Books',
            'slug'     => 'books',
            'position' => 3,
            'level'    => 1,
        ]);
    }
}