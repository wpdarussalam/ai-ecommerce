<div>
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb / Tombol Kembali -->
        <div class="mb-6">
            <a href="/" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Katalog
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Gambar Produk -->
            <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
                <img 
                    src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600' }}" 
                    alt="{{ $product->name }}" 
                    class="w-full h-full object-cover"
                >
            </div>

            <!-- Informasi Produk -->
            <div class="flex flex-col justify-between">
                <div>
                    <span class="inline-block bg-blue-50 text-blue-600 text-xs px-2.5 py-1 rounded-md font-semibold mb-3">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </span>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        {{ $product->name }}
                    </h1>
                    <p class="text-2xl font-extrabold text-blue-600 mb-6">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <div class="border-t border-b border-gray-100 py-4 my-4">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            {{ $product->description ?? 'Belum ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>

                    <div class="text-sm text-gray-500 mb-6">
                        Stok Tersedia: <span class="font-semibold text-gray-800">{{ $product->stock ?? 0 }} pcs</span>
                    </div>
                </div>

                <!-- Kontrol Jumlah & Tombol Beli -->
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-gray-700">Jumlah:</span>
                        <div class="flex items-center border border-gray-200 rounded-lg">
                            <button wire:click="decrementQuantity" class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 transition">-</button>
                            <span class="px-4 py-1.5 font-semibold text-sm">{{ $quantity }}</span>
                            <button wire:click="incrementQuantity" class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 transition">+</button>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button 
                            wire:click="addToCart" 
                            class="flex-1 bg-gray-900 hover:bg-blue-600 text-white font-semibold py-3 rounded-xl transition duration-200">
                            + Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>