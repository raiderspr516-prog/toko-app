@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- Hero banner --}}
<div class="gradient-brand relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-14 md:py-20 text-center relative z-10">
        <p class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-3">🎉 Promo Spesial Hari Ini</p>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-3">Belanja Puas, Harga Pas!</h1>
        <p class="text-white/90 max-w-xl mx-auto mb-6">Ribuan produk pilihan, gratis ongkir belanja di atas Rp500rb, kirim ke seluruh Indonesia.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-white text-orange-600 font-bold px-6 py-3 rounded-full hover:bg-orange-50 shadow-lg">
            Mulai Belanja Sekarang 🛒
        </a>
    </div>
    <div class="absolute -bottom-6 -right-6 text-9xl opacity-10 select-none">🛍️</div>
    <div class="absolute -top-6 -left-6 text-9xl opacity-10 select-none">🎁</div>
</div>

{{-- Kategori quick access --}}
<div class="max-w-7xl mx-auto px-4 -mt-8 relative z-10">
    <div class="bg-white rounded-2xl shadow-md p-4 grid grid-cols-3 sm:grid-cols-6 gap-3">
        @foreach (\App\Models\Category::active()->orderBy('name')->take(6)->get() as $cat)
            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="flex flex-col items-center gap-1 text-center group">
                <span class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-xl group-hover:bg-orange-100">🗂️</span>
                <span class="text-xs text-gray-600 group-hover:text-orange-600 line-clamp-1">{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center gap-2 mb-4">
        <span class="text-2xl">🔥</span>
        <h2 class="font-bold text-gray-800 text-lg">Flash Sale & Unggulan</h2>
        <a href="{{ route('products.index') }}" class="ml-auto text-sm text-orange-600 hover:underline font-medium">Lihat semua &rarr;</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse ($featured as $product)
            @include('customer.products._card', ['product' => $product])
        @empty
            <p class="col-span-4 text-center text-gray-400 py-10">Belum ada produk unggulan.</p>
        @endforelse
    </div>

    <div class="flex items-center gap-2 mt-12 mb-4">
        <span class="text-2xl">🆕</span>
        <h2 class="font-bold text-gray-800 text-lg">Baru Ditambahkan</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse ($latest as $product)
            @include('customer.products._card', ['product' => $product])
        @empty
            <p class="col-span-4 text-center text-gray-400 py-10">Belum ada produk.</p>
        @endforelse
    </div>
</div>
@endsection
