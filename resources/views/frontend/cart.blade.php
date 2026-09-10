@extends('layouts.frontend')

@section('content')

<h1 class="text-xl font-bold text-gray-800 mb-4"><i class="fa-solid fa-cart-shopping text-blue-600 mr-2"></i>Keranjang Belanja</h1>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

@if(count($cart) > 0)
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- List Produk di Keranjang -->
        <div class="md:col-span-8 bg-white p-4 rounded-sm shadow-sm">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="p-3">Produk</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3 text-center">Jumlah</th>
                        <th class="p-3 text-right">Subtotal</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $details)
                        @php 
                            $subtotal = $details['price'] * $details['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="p-3 flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 flex-none rounded overflow-hidden">
                                    @if($details['image'])
                                        <img src="{{ asset('storage/' . $details['image']) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <span class="font-medium text-gray-800">{{ $details['name'] }}</span>
                            </td>
                            <td class="p-3">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                            <td class="p-3 text-center">{{ $details['quantity'] }}</td>
                            <td class="p-3 text-right font-semibold text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Belanja -->
        <div class="md:col-span-4 bg-white p-4 rounded-sm shadow-sm h-fit">
            <h2 class="font-bold text-gray-700 border-b pb-2 mb-4">Ringkasan Belanja</h2>
            <div class="flex justify-between text-sm mb-4">
                <span>Total Harga</span>
                <span class="font-bold text-lg text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <button class="w-full bg-blue-600 text-white py-2.5 rounded-sm font-semibold hover:bg-blue-700 transition">
                <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-blue-600 text-white py-2.5 rounded-sm font-semibold hover:bg-blue-700 transition">              
                Lanjut ke Checkout
                </a>
            </button>
        </div>
    </div>
@else
    <div class="bg-white p-8 text-center rounded-sm shadow-sm">
        <i class="fa-solid fa-cart-flatbed text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 text-sm">Keranjang belanja kamu masih kosong.</p>
        <a href="/" class="inline-block mt-4 bg-blue-600 text-white text-xs font-bold px-5 py-2.5 rounded-sm hover:bg-blue-700">Mulai Belanja</a>
    </div>
@endif

@endsection