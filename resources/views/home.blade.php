@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="bg-emerald-600 text-white text-center py-16 px-4">
    <h1 class="text-3xl md:text-4xl font-bold mb-2">Belanja Mudah di {{ config('app.name') }}</h1>
    <p class="text-emerald-100">Ribuan produk, harga bersaing, kirim ke seluruh Indonesia.</p>
</div>

<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-gray-800 text-lg">Produk Unggulan</h2>
        <a href="{{ route('products.index') }}" class="text-sm text-emerald-600 hover:underline">Lihat semua &rarr;</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse ($featured as $product)
            @include('customer.products._card', ['product' => $product])
        @empty
            <p class="col-span-4 text-center text-gray-400 py-10">Belum ada produk unggulan.</p>
        @endforelse
    </div>

    <div class="flex items-center justify-between mt-10 mb-4">
        <h2 class="font-semibold text-gray-800 text-lg">Produk Terbaru</h2>
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
