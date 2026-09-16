<header class="bg-blue-600 text-white shadow-md sticky top-0 z-50">
    <!-- Top Utility Bar -->
    <div class="bg-blue-700 text-xs py-1.5 px-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex gap-4">
                <a href="#" class="hover:underline">Bantuan</a>
                <span>|</span>
                <a href="#" class="hover:underline">Hubungi Kami</a>
            </div>
            <div>
                <a href="/admin" class="hover:underline flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Panel Admin
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 text-xl font-bold tracking-tight text-white">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            Aura Commerce
        </a>

        <!-- Search Bar -->
        <div class="flex-1 max-w-xl mx-4">
            <form action="/" method="GET" class="flex">
                <input 
                    type="text" 
                    name="search"
                    placeholder="Cari produk di Aura Commerce..." 
                    class="w-full px-4 py-2 rounded-l-lg text-gray-800 text-sm focus:outline-none bg-white"
                >
                <button type="submit" class="bg-blue-800 hover:bg-blue-900 px-5 rounded-r-lg flex items-center justify-center text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Cart Badge Dynamic -->
        <a href="/cart" class="relative p-2 hover:bg-blue-500 rounded-lg transition">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-blue-600">
                {{ $cartCount }}
            </span>
        </a>
    </div>
</header>