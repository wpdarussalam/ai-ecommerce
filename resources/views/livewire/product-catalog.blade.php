<div class="container mx-auto px-4 py-6">

    <!-- INPUT PENCARIAN & FILTER KATEGORI -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-8 space-y-4">
        
        <!-- Input Live Search -->
        <div class="relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari nama atau deskripsi produk..." 
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 text-sm transition-all duration-200"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Filter Kategori -->
        <div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3">Kategori</span>
            
            <div class="flex flex-wrap gap-2">
                <!-- Tombol 'Semua' -->
                <button 
                    wire:click="setCategory('semua')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 flex items-center gap-2 {{ $selectedCategory === 'semua' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <span>::</span> Semua
                </button>

                <!-- Tombol Kategori dari Database -->
                @foreach($categories as $cat)
                    <button 
                        wire:click="setCategory('{{ $cat->slug }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 flex items-center gap-2 {{ $selectedCategory === $cat->slug ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <svg class="w-4 h-4 opacity-70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.265 0 .52.105.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

    </div>

    <!-- DAFTAR KATALOG PRODUK -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 overflow-hidden transition duration-300 flex flex-col justify-between">
                <div>
                    <!-- Gambar Produk -->
                    <div class="aspect-square bg-gray-50 overflow-hidden relative">
                        <img 
                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                        >
                        <span class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-gray-800 text-xs px-2 py-1 rounded-md font-semibold">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>

                    <!-- Informasi Produk -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 text-base line-clamp-2 mb-2" title="{{ $product->name }}">
                            {{ $product->name }}
                        </h3>
                        <p class="text-blue-600 font-bold text-lg">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="p-4 pt-0">
                    <a href="#" class="w-full block text-center bg-gray-900 hover:bg-blue-600 text-white text-sm font-semibold py-2.5 rounded-xl transition duration-200">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-2xl border border-dashed border-gray-200">
                <p class="text-lg font-medium">
                    @if($search)
                        Produk dengan kata kunci "<span class="text-gray-900 font-semibold">{{ $search }}</span>" tidak ditemukan.
                    @else
                        Belum ada produk untuk kategori ini.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- LINK PAGINATION -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>