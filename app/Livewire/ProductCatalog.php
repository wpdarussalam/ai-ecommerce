<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;

class ProductCatalog extends Component
{
    public $selectedCategory = 'semua';

    public function setCategory($slug)
    {
        $this->selectedCategory = $slug;
    }

    public function render()
    {
        $categories = Category::where('status', true)
            ->orderBy('position', 'asc')
            ->get();

        $products = Product::query()
            ->where('status', true)
            ->when($this->selectedCategory !== 'semua', function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', $this->selectedCategory)
                      ->orWhereHas('parent', fn ($p) => $p->where('slug', $this->selectedCategory));
                });
            })
            ->latest()
            ->get();

        return view('livewire.product-catalog', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}