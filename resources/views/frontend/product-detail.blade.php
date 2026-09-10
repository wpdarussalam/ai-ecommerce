@extends('layouts.frontend')

@section('content')

<!-- Breadcrumb -->
<div class="text-xs text-gray-500 mb-4 flex items-center gap-2">
    <a href="/" class="hover:text-blue-600">Beranda</a>
    <span>/</span>
    <span class="text-gray-800 font-medium">{{ $product->name }}</span>
</div>

<!-- Card Utama Detail Produk -->
<div class="bg-white rounded-sm shadow-sm p-6 grid grid-cols-1 md:grid-cols-12 gap-8 mb-8">
    <!-- Gambar Produk -->
    <div class="md:col-span-5">
        <div class="w-full h-80 bg-gray-100 rounded-sm overflow-hidden flex items-center justify-center border border-gray-200">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <span class="text-gray-400 text-sm">Tidak Ada Gambar</span>
            @endif
        </div>
    </div>

    <!-- Info Produk -->
    <div class="md:col-span-7 flex flex-col justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->name }}</h1>
            
            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
                <span class="text-orange-500 font-bold"><i class="fa-solid fa-star"></i> 5.0</span>
                <span>|</span>
                <span>Stok Tersedia: <strong class="text-gray-700">{{ $product->stock }}</strong></span>
            </div>

            <!-- Box Harga -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="text-3xl font-bold text-blue-600">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
            </div>

            <!-- Deskripsi Singkat -->
            <div class="text-sm text-gray-600 mb-6 leading-relaxed">
                <h3 class="font-bold text-gray-700 mb-1">Deskripsi Produk:</h3>
                <p>{{ $product->description ?? 'Belum ada deskripsi untuk produk ini.' }}</p>
            </div>
        </div>

        <!-- Form Tambah Ke Keranjang -->
        <div class="flex gap-4 border-t pt-6">
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-blue-100 border border-blue-600 text-blue-600 py-3 rounded-sm font-semibold hover:bg-blue-200 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-plus"></i> + Keranjang
                </button>
            </form>
            
            <button class="flex-1 bg-blue-600 text-white py-3 rounded-sm font-semibold hover:bg-blue-700 transition">
                Beli Sekarang
            </button>
        </div>
    </div>
</div>

@endsection