@extends('layouts.frontend')

@section('content')

<div class="max-w-md mx-auto bg-white p-8 rounded-sm shadow-sm text-center my-8">
    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
        <i class="fa-solid fa-check"></i>
    </div>
    
    <h1 class="text-xl font-bold text-gray-800 mb-2">Pesanan Berhasil Dibuat!</h1>
    <p class="text-xs text-gray-500 mb-4">Terima kasih telah berbelanja. Pesanan kamu sedang kami proses.</p>

    <!-- Nomor Order -->
    <div class="bg-gray-50 border border-dashed border-gray-300 p-3 rounded mb-6 font-mono font-bold text-blue-600 text-sm">
        {{ $order->order_number ?? 'ORD-' . $order->id }}
    </div>    

    <!-- Rincian Pesanan -->
    <div class="text-left text-xs text-gray-600 border-t border-b py-4 mb-6 space-y-2">
        <p><strong>Penerima:</strong> {{ $order->customer->name ?? '-' }}</p>
        <p><strong>No. HP:</strong> {{ $order->customer->phone ?? '-' }}</p>
        <p><strong>Total Tagihan:</strong> <span class="text-blue-600 font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
        <p><strong>Status:</strong> <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-[10px] uppercase font-bold">{{ $order->status }}</span></p>
    </div>

    <a href="/" class="inline-block w-full bg-blue-600 text-white py-2.5 rounded-sm font-semibold text-xs hover:bg-blue-700 transition">
        Kembali ke Beranda
    </a>
</div>

@endsection