<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Navbar extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    // Mendengarkan event 'cart-updated' dari komponen ProductDetail
    #[On('cart-updated')]
    public function updateCartCount()
    {
        $cart = session()->get('cart', []);
        
        // Hitung total jumlah item yang ada di keranjang
        $this->cartCount = array_reduce($cart, function ($total, $item) {
            return $total + ($item['quantity'] ?? 0);
        }, 0);
    }

    public function render()
    {
        return view('components.navbar');
    }
}