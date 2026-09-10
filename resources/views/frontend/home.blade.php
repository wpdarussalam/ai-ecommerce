@extends('layouts.frontend')

@section('content')

<!-- 1. Hero Banner Promo -->
<div class="mb-8 rounded-lg overflow-hidden shadow-sm">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 md:p-12 flex items-center justify-between">
        <div>
            <span class="bg-yellow-400 text-blue-900 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Promo Spesial</span>
            <h1 class="text-2xl md:text-4xl font-extrabold mt-3 mb-2">Diskon Elektronik Hingga 50%</h1>
            <p class="text-blue-100 text-sm mb-6">Dapatkan produk impianmu dengan harga terbaik hari ini.</p>
            <a href="#produk" class="inline-block bg-white text-blue-600 font-bold px-5 py-2.5 rounded hover:bg-gray-100 transition shadow-sm text-xs">
                Belanja Sekarang
            </a>
        </div>
        <i class="fa-solid fa-bolt text-8xl opacity-20 hidden md:block mr-6"></i>
    </div>
</div>

<!-- 2. Value Proposition / Keunggulan Toko -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 rounded-md shadow-sm border border-gray-100 flex items-center gap-3">
        <i class="fa-solid fa-truck-fast text-2xl text-blue-600"></i>
        <div>
            <h4 class="text-xs font-bold text-gray-800">Pengiriman Cepat</h4>
            <p class="text-[10px] text-gray-500">Layanan ekspedisi terpercaya</p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-md shadow-sm border border-gray-100 flex items-center gap-3">
        <i class="fa-solid fa-shield-halved text-2xl text-blue-600"></i>
        <div>
            <h4 class="text-xs font-bold text-gray-800">Garansi Resmi</h4>
            <p class="text-[10px] text-gray-500">100% Produk Original</p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-md shadow-sm border border-gray-100 flex items-center gap-3">
        <i class="fa-solid fa-credit-card text-2xl text-blue-600"></i>
        <div>
            <h4 class="text-xs font-bold text-gray-800">Pembayaran Aman</h4>
            <p class="text-[10px] text-gray-500">Transaksi terverifikasi</p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-md shadow-sm border border-gray-100 flex items-center gap-3">
        <i class="fa-solid fa-headset text-2xl text-blue-600"></i>
        <div>
            <h4 class="text-xs font-bold text-gray-800">Layanan 24/7</h4>
            <p class="text-[10px] text-gray-500">Dukungan CS siap bantu</p>
        </div>
    </div>
</div>

<!-- 3. Section Kategori -->
<div class="bg-white p-4 rounded-md shadow-sm border border-gray-100 mb-8">
    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Kategori</h2>
    <div class="flex flex-wrap gap-2">
        <a href="#" class="px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 rounded-full text-xs font-semibold flex items-center gap-2 hover:bg-blue-100 transition">
            <i class="fa-solid fa-border-all"></i> Semua
        </a>
        @foreach($categories as $category)
            <a href="?category={{ $category->id }}" class="px-4 py-2 bg-gray-50 text-gray-700 border border-gray-200 rounded-full text-xs font-medium flex items-center gap-2 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition">
                <i class="fa-solid fa-tag text-blue-500"></i> {{ $category->name }}
            </a>
        @endforeach
    </div>
</div>

<!-- 4. Section Rekomendasi Produk -->
<div id="produk" class="mb-8">
    <div class="flex items-center justify-between mb-4 border-b pb-2">
        <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-fire text-amber-500"></i> REKOMENDASI PRODUK
        </h2>
    </div>

    <!-- Grid Produk -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($products as $product)
            <div class="bg-white rounded-md border border-gray-100 shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col justify-between group">
                <div>
                    <!-- Gambar Produk & Badge -->
                    <div class="relative bg-gray-50 aspect-square flex items-center justify-center p-3 overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-contain h-full w-full group-hover:scale-105 transition duration-300">
                        @else
                            <div class="text-gray-400 text-xs flex flex-col items-center">
                                <i class="fa-regular fa-image text-3xl mb-1"></i>
                                No Image
                            </div>
                        @endif
                    </div>

                    <!-- Informasi Produk -->
                    <div class="p-3">
                        <h3 class="text-xs font-semibold text-gray-800 line-clamp-2 h-8 mb-1" title="{{ $product->name }}">
                            {{ $product->name }}
                        </h3>
                        <p class="text-blue-600 font-bold text-sm">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        
                        <div class="flex items-center justify-between text-[11px] text-gray-400 mt-2">
                            <span>Stok: {{ $product->stock ?? 0 }}</span>
                            <span class="text-amber-500 font-medium flex items-center gap-1">
                                <i class="fa-solid fa-star text-[10px]"></i> 5.0
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tombol + Keranjang -->
                <div class="p-3 pt-0">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 rounded transition flex items-center justify-center gap-1">
                            <i class="fa-solid fa-cart-plus"></i> + Keranjang
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-8 text-center text-gray-500 text-sm rounded border">
                Belum ada produk yang tersedia saat ini.
            </div>
        @endforelse
    </div>
</div>

@endsection