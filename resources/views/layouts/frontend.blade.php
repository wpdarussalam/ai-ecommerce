<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Commerce</title>
    <!-- CDN Tailwind CSS & FontAwesome Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col justify-between">

    <!-- Cukup panggil komponen Livewire Navbar di sini -->
    <livewire:navbar />
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-6 flex-grow w-full">
        {{ $slot ?? '' }}
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