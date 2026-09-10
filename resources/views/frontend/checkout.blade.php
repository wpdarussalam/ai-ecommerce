@extends('layouts.frontend')

@section('content')

<h1 class="text-xl font-bold text-gray-800 mb-6">
    <i class="fa-solid fa-file-invoice text-blue-600 mr-2"></i>Checkout Pesanan
</h1>

<form action="{{ route('checkout.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <!-- Form Data Pembeli & Alamat -->
        <div class="md:col-span-7 bg-white p-6 rounded-sm shadow-sm">
            <h2 class="font-bold text-gray-800 border-b pb-3 mb-4 text-sm uppercase">Informasi Pengiriman</h2>

            <!-- Nama Lengkap -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                <input type="text" name="customer_name" class="w-full border border-gray-300 p-2.5 rounded text-sm focus:outline-none focus:border-blue-600" placeholder="Masukkan nama penerima" required>
            </div>

            <!-- Email (Sudah dipindahkan ke sini) -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 p-2.5 rounded text-sm focus:outline-none focus:border-blue-600" placeholder="contoh@gmail.com" required>
            </div>

            <!-- Nomor HP / WhatsApp -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor WhatsApp / HP</label>
                <input type="text" name="customer_phone" class="w-full border border-gray-300 p-2.5 rounded text-sm focus:outline-none focus:border-blue-600" placeholder="Contoh: 08123456789" required>
            </div>

            <!-- Alamat Pengiriman -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Lengkap Pengiriman</label>
                <textarea name="address" rows="4" class="w-full border border-gray-300 p-2.5 rounded text-sm focus:outline-none focus:border-blue-600" placeholder="Jalan, No. Rumah, RT/RW, Kecamatan, Kota" required></textarea>
            </div>
        </div>

        <!-- Ringkasan Item Pesanan -->
        <div class="md:col-span-5 bg-white p-6 rounded-sm shadow-sm h-fit">
            <h2 class="font-bold text-gray-800 border-b pb-3 mb-4 text-sm uppercase">Rincian Pesanan</h2>

            <div class="divide-y mb-4">
                @php $total = 0; @endphp
                @foreach($cart as $item)
                    @php 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp
                    <div class="py-2 flex justify-between text-xs">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item['name'] }}</p>
                            <p class="text-gray-400">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <span class="font-semibold text-gray-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t pt-4 mb-6 flex justify-between items-center">
                <span class="font-bold text-gray-800">Total Pembayaran</span>
                <span class="text-xl font-bold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-sm font-semibold hover:bg-blue-700 transition">
                Buat Pesanan Sekarang
            </button>
        </div>

    </div>
</form>

@endsection