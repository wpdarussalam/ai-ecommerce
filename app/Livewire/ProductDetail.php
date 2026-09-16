<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductDetail extends Component
{
    public $product;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->product = Product::with('category')
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function render()
    {
       return view('livewire.product-detail')
        ->layout('layouts.frontend');
    }

    public function addToCart()
{
    // Ambil data keranjang dari session (jika belum ada, inisialisasi array kosong)
    $cart = session()->get('cart', []);

    $productId = $this->product->id;

    if (isset($cart[$productId])) {
        // Jika produk sudah ada di keranjang, tambahkan kuantitasnya
        $cart[$productId]['quantity'] += $this->quantity;
    } else {
        // Jika belum ada, masukkan data produk baru
        $cart[$productId] = [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'image' => $this->product->image,
            'quantity' => $this->quantity,
        ];
    }

    // Simpan kembali ke session
    session()->put('cart', $cart);

    // Kirim event agar komponen Header/Navbar memperbarui badge keranjang
    $this->dispatch('cart-updated');

    // Tampilkan notifikasi (opsional)
    session()->flash('message', 'Produk berhasil ditambahkan ke keranjang!');
}
}