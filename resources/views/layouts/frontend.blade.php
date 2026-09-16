
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Commerce</title>
    <!-- CDN Tailwind CSS & FontAwesome Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- 1. SINKRONISASI STYLES LIVEWIRE -->
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans">

    <!-- Top Bar Header (Tema Biru Penuh) -->
<header class="bg-blue-600 text-white sticky top-0 z-50 shadow-md">
    <!-- Mini Header / Top Navigation -->
    <div class="max-w-7xl mx-auto px-4 py-1 flex justify-between text-xs border-b border-blue-500 bg-blue-700">
        <div class="flex space-x-4">
            <a href="#" class="hover:opacity-80">Bantuan</a>
            <span>|</span>
            <a href="#" class="hover:opacity-80">Hubungi Kami</a>
        </div>
        <div class="flex space-x-4">
            <a href="/admin" class="hover:opacity-80 font-bold"><i class="fa-solid fa-user-gear mr-1"></i> Panel Admin</a>
        </div>
    </div>

        <!-- Main Search Bar Header -->
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-6">
            <!-- Logo -->
            <a href="/" class="text-2xl font-bold tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-bag-shopping text-3xl"></i>
                <span>Aura Commerce</span>
            </a>

            <!-- Search Bar -->
            <div class="flex-1 max-w-2xl relative">
                <form action="#" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Cari produk di Aura Commerce..." 
                           class="w-full py-2 px-4 rounded-l-sm text-gray-800 text-sm focus:outline-none">
                    <button type="submit" class="bg-blue-800 px-6 py-2 rounded-r-sm hover:bg-blue-900 transition">
                        <i class="fa-solid fa-magnifying-glass text-white"></i>
                    </button>
                </form>
            </div>

            <!-- Keranjang Belanja Navbar -->
			<div class="relative">
			    <a href="{{ route('cart.index') }}" class="text-2xl px-2 hover:opacity-80 relative">
			        <i class="fa-solid fa-cart-shopping"></i>
			        <span class="absolute -top-2 -right-2 bg-white text-blue-600 text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center border border-blue-600">
			            {{ count((array) session('cart')) }}
			        </span>
			    </a>
			</div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12 py-8 text-sm text-gray-600">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} <strong>Aura Commerce</strong>. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
@livewireScripts
</body>
</html>