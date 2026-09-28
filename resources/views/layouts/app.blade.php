<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">
    <nav class="bg-white border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-600">
                🛍️ {{ config('app.name') }}
            </a>
            <div class="hidden md:flex items-center gap-6 text-sm text-gray-600">
                <a href="{{ route('products.index') }}" class="hover:text-emerald-600">Produk</a>
                <a href="{{ route('products.index') }}" class="hover:text-emerald-600">Kategori</a>
                <a href="{{ route('cart.index') }}" class="hover:text-emerald-600">Keranjang</a>
            </div>
            <div class="flex items-center gap-4 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-emerald-600">Halo, {{ auth()->user()->name }}</a>
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-600 hover:text-emerald-600">
                        🔔
                        @if (auth()->user()->unreadNotifications()->count())
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">{{ auth()->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-emerald-600">Pesanan Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-600 hover:underline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-emerald-600">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="mt-16 border-t bg-white">
        <div class="max-w-7xl mx-auto px-4 py-8 text-sm text-gray-500 text-center">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Dibangun dengan Laravel.
        </div>
    </footer>
</body>
</html>
