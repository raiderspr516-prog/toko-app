<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - @yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">
    <div class="min-h-screen flex">
        <aside class="w-64 gradient-brand text-white flex-shrink-0 hidden md:flex md:flex-col">
            <div class="p-5 border-b border-white/20">
                <p class="text-xl font-extrabold flex items-center gap-2">🛍️ {{ config('app.name') }}</p>
                <span class="text-xs font-medium text-white/70">Admin Panel</span>
            </div>
            <nav class="mt-2 space-y-0.5 text-sm flex-1 overflow-y-auto pb-4">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                        ['route' => 'admin.products.index', 'pattern' => 'admin.products.*', 'icon' => '📦', 'label' => 'Produk'],
                        ['route' => 'admin.categories.index', 'pattern' => 'admin.categories.*', 'icon' => '🗂️', 'label' => 'Kategori'],
                        ['route' => 'admin.orders.index', 'pattern' => 'admin.orders.*', 'icon' => '🧾', 'label' => 'Pesanan'],
                        ['route' => 'admin.payment-verification.index', 'pattern' => 'admin.payment-verification.*', 'icon' => '💳', 'label' => 'Verifikasi Pembayaran'],
                        ['route' => 'admin.customers.index', 'pattern' => 'admin.customers.*', 'icon' => '👥', 'label' => 'Customer'],
                        ['route' => 'admin.coupons.index', 'pattern' => 'admin.coupons.*', 'icon' => '🏷️', 'label' => 'Kupon'],
                        ['route' => 'admin.reports.sales', 'pattern' => 'admin.reports.*', 'icon' => '📈', 'label' => 'Laporan'],
                        ['route' => 'admin.settings.edit', 'pattern' => 'admin.settings.*', 'icon' => '⚙️', 'label' => 'Pengaturan'],
                    ];
                @endphp
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 mx-3 my-0.5 px-3 py-2.5 rounded-xl transition
                              {{ request()->routeIs($item['pattern']) ? 'bg-white text-orange-600 font-semibold shadow' : 'text-white/90 hover:bg-white/15' }}">
                        <span class="text-base">{{ $item['icon'] }}</span> {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white shadow-sm flex items-center justify-between px-6 py-4">
                <h1 class="text-lg font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                <div class="text-sm text-gray-500 flex items-center gap-5">
                    <a href="{{ route('admin.notifications.index') }}" class="relative text-lg">
                        🔔
                        @if (auth('admin')->user()->unreadNotifications()->count())
                            <span class="absolute -top-1 -right-1 bg-pink-600 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">{{ auth('admin')->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </a>
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full gradient-brand text-white flex items-center justify-center text-xs font-bold">
                            {{ Str::substr(auth('admin')->user()->name, 0, 1) }}
                        </span>
                        <span class="font-medium text-gray-700">{{ auth('admin')->user()->name }}</span>
                    </div>
                </div>
            </header>
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
