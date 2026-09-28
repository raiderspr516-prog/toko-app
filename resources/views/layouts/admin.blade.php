<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - @yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-gray-900 text-gray-200 flex-shrink-0 hidden md:block">
            <div class="p-4 text-xl font-bold text-white border-b border-gray-800">
                🛍️ {{ config('app.name') }} <span class="text-xs font-normal text-gray-400 block">Admin Panel</span>
            </div>
            <nav class="mt-4 space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">📊 Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 text-white' : '' }}">📦 Produk</a>
                <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 text-white' : '' }}">🗂️ Kategori</a>
                <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-white' : '' }}">🧾 Pesanan</a>
                <a href="{{ route('admin.payment-verification.index') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.payment-verification.*') ? 'bg-gray-800 text-white' : '' }}">💳 Verifikasi Pembayaran</a>
                <a href="{{ route('admin.customers.index') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.customers.*') ? 'bg-gray-800 text-white' : '' }}">👥 Customer</a>
                <a href="{{ route('admin.coupons.index') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.coupons.*') ? 'bg-gray-800 text-white' : '' }}">🏷️ Kupon</a>
                <a href="{{ route('admin.reports.sales') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.reports.*') ? 'bg-gray-800 text-white' : '' }}">📈 Laporan</a>
                <a href="{{ route('admin.settings.edit') }}" class="block px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-800 text-white' : '' }}">⚙️ Pengaturan</a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow flex items-center justify-between px-6 py-3">
                <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                <div class="text-sm text-gray-500 flex items-center gap-4">
                    <a href="{{ route('admin.notifications.index') }}" class="relative">
                        🔔
                        @if (auth('admin')->user()->unreadNotifications()->count())
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">{{ auth('admin')->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </a>
                    <span>Admin Panel</span>
                </div>
            </header>
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
