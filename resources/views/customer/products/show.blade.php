@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow overflow-hidden md:flex">
        <div class="md:w-1/2 h-72 md:h-auto bg-gray-100 flex items-center justify-center">
            @if ($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
            @else
                <span class="text-6xl">📦</span>
            @endif
        </div>
        @if ($product->images->count())
        <div class="hidden"></div>
        @endif
        <div class="p-6 md:w-1/2">
            <span class="text-xs text-emerald-600 uppercase font-semibold">{{ $product->category->name }}</span>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
            <p class="text-xs text-gray-400 mb-4">SKU: {{ $product->sku }}</p>

            @if ($product->discount_price)
                <p class="text-gray-400 line-through">Rp{{ number_format($product->price,0,',','.') }}</p>
                <p class="text-3xl font-bold text-red-600 mb-4">Rp{{ number_format($product->discount_price,0,',','.') }}</p>
            @else
                <p class="text-3xl font-bold text-gray-900 mb-4">Rp{{ number_format($product->price,0,',','.') }}</p>
            @endif

            <p class="text-gray-600 mb-6">{{ $product->description }}</p>

            @if ($product->isPurchasable())
                <p class="text-sm text-gray-500 mb-3">Stok tersedia: {{ $product->stock }}</p>
                <form method="POST" action="{{ route('cart.store', $product) }}" class="flex items-center gap-3">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-lg border-gray-300">
                    <button name="action" value="add" class="bg-white border border-emerald-600 text-emerald-600 px-5 py-2.5 rounded-lg hover:bg-emerald-50">
                        + Keranjang
                    </button>
                    <button name="action" value="buy_now" class="bg-emerald-600 text-white px-5 py-2.5 rounded-lg hover:bg-emerald-700">
                        Beli Sekarang
                    </button>
                </form>
            @else
                <span class="inline-block bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-medium">Out of Stock</span>
            @endif
        </div>
    </div>

    @if ($related->count())
    <h3 class="font-semibold text-gray-800 mt-10 mb-4">Produk Terkait</h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach ($related as $r)
            @include('customer.products._card', ['product' => $r])
        @endforeach
    </div>
    @endif
</div>
@endsection
