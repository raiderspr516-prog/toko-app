<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    {{-- Top gradient bar --}}
    <nav class="gradient-brand sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-4 h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-1.5 text-xl font-extrabold text-white flex-shrink-0">
                    <span class="text-2xl">🛍️</span> {{ config('app.name') }}
                </a>

                <form method="GET" action="{{ route('products.index') }}" class="flex-1 hidden sm:flex">
                    <div class="w-full flex bg-white rounded-full overflow-hidden shadow-inner">
                        <input type="text" name="cari" placeholder="Cari produk favoritmu..." class="flex-1 px-4 py-2 text-sm border-0 focus:ring-0 rounded-full">
                        <button class="px-5 bg-white text-orange-500 hover:text-pink-600">
                            🔍
                        </button>
                    </div>
                </form>

                <div class="flex items-center gap-4 text-sm text-white ml-auto">
                    <a href="{{ route('cart.index') }}" class="relative hover:opacity-80" title="Keranjang">
                        <span class="text-xl">🛒</span>
                        @auth
                            @php $cartCount = auth()->user()->cart?->items->sum('quantity') ?? 0; @endphp
                            @if ($cartCount)
                                <span class="absolute -top-2 -right-2 bg-white text-orange-600 text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ $cartCount }}</span>
                            @endif
                        @endauth
                    </a>

                    @auth
                        <a href="{{ route('notifications.index') }}" class="relative hover:opacity-80" title="Notifikasi">
                            <span class="text-xl">🔔</span>
                            @if (auth()->user()->unreadNotifications()->count())
                                <span class="absolute -top-2 -right-2 bg-white text-pink-600 text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ auth()->user()->unreadNotifications()->count() }}</span>
                            @endif
                        </a>
                        <div class="hidden md:flex items-center gap-3">
                            <a href="{{ route('orders.index') }}" class="hover:opacity-80">Pesanan</a>
                            <a href="{{ route('dashboard') }}" class="font-semibold hover:opacity-80">Hai, {{ Str::before(auth()->user()->name, ' ') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-full text-xs font-medium">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hover:opacity-80">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-white text-orange-600 font-semibold px-4 py-1.5 rounded-full hover:bg-orange-50">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Category quick links --}}
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 flex items-center gap-6 h-11 text-sm text-gray-600 overflow-x-auto">
            <a href="{{ route('products.index') }}" class="whitespace-nowrap hover:text-orange-600 font-medium">🔥 Semua Produk</a>
            @foreach (\App\Models\Category::active()->orderBy('name')->take(8)->get() as $navCat)
                <a href="{{ route('products.index', ['category' => $navCat->slug]) }}" class="whitespace-nowrap hover:text-orange-600">{{ $navCat->name }}</a>
            @endforeach
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="mt-16 bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">
            <div>
                <p class="text-white font-bold text-lg mb-2">🛍️ {{ config('app.name') }}</p>
                <p class="text-gray-400">Belanja online mudah, cepat, dan terpercaya untuk semua kebutuhanmu.</p>
            </div>
            <div>
                <p class="text-white font-semibold mb-2">Belanja</p>
                <ul class="space-y-1 text-gray-400">
                    <li><a href="{{ route('products.index') }}" class="hover:text-orange-400">Semua Produk</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-orange-400">Keranjang</a></li>
                </ul>
            </div>
            <div>
                <p class="text-white font-semibold mb-2">Akun</p>
                <ul class="space-y-1 text-gray-400">
                    @auth
                        <li><a href="{{ route('orders.index') }}" class="hover:text-orange-400">Pesanan Saya</a></li>
                        <li><a href="{{ route('addresses.index') }}" class="hover:text-orange-400">Alamat Saya</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-orange-400">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-orange-400">Daftar</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <p class="text-white font-semibold mb-2">Bantuan</p>
                <p class="text-gray-400">{{ \App\Models\Setting::get('store_email', '-') }}</p>
                <p class="text-gray-400">{{ \App\Models\Setting::get('store_phone', '-') }}</p>
            </div>
        </div>
        <div class="border-t border-gray-800 py-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Dibangun dengan Laravel.
        </div>
    </footer>
</body>
</html>
