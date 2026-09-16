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

<!-- 3. Integrasi Livewire Volt Catalog (Filter Kategori & Produk Real-time) -->
<div id="produk">
  <livewire:product-catalog />
</div>

@endsection