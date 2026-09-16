<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ProductCatalog extends Component
{
    use WithPagination; // 1. Masukkan Trait ini di sini

    public $selectedCategory = 'semua';

    #[Url(history: true)] // 2. Tambahkan atribut URL agar query pencarian tersimpan di URL
    public $search = '';

    public function setCategory($slug)
    {
        $this->selectedCategory = $slug;
    }

    // Otomatis balik ke halaman 1 jika user mengganti kategori
    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    // Otomatis balik ke halaman 1 jika user mengetik pencarian baru
    public function updatingSearch()
    {
        $this->selectedCategory = 'semua';
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::where('status', true)
            ->orderBy('position', 'asc')
            ->get();

        $products = Product::query()
            ->where('status', true)
            // 1. Filter Kategori
            ->when($this->selectedCategory !== 'semua', function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', $this->selectedCategory)
                      ->orWhereHas('parent', fn ($p) => $p->where('slug', $this->selectedCategory));
                });
            })
            // 2. Filter Pencarian
            // Filter Pencarian (Cari berdasarkan Nama Produk, Deskripsi, DAN Kategori)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($catQuery) {
                          $catQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->latest()
            ->paginate(12);

        return view('livewire.product-catalog', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}