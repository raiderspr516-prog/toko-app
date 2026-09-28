@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-xl font-bold text-gray-800 mb-4">Keranjang Belanja</h1>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    @if ($cart->items->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400">
            <p class="text-4xl mb-2">🛒</p>
            <p>Keranjang kamu masih kosong.</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-orange-500 text-white px-5 py-2 rounded-xl text-sm hover:bg-orange-600">Mulai Belanja</a>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm divide-y">
            @foreach ($cart->items as $item)
            <div class="p-4 flex items-center gap-4">
                <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                    @if ($item->product->image)
                        <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover rounded">
                    @else
                        <span class="text-2xl">📦</span>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                    <p class="text-sm text-gray-500">Rp{{ number_format($item->product->finalPrice(),0,',','.') }}</p>
                    @if (!$item->product->isPurchasable())
                        <p class="text-xs text-red-600">Produk ini sudah tidak tersedia — akan dihapus otomatis saat checkout.</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('cart.update', $item->product) }}" class="flex items-center gap-2">
                    @csrf @method('PATCH')
                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-16 rounded-xl border-gray-300 text-sm">
                    <button class="text-xs text-orange-600 hover:underline">Update</button>
                </form>
                <p class="w-28 text-right font-semibold text-gray-800">Rp{{ number_format($item->subtotal(),0,',','.') }}</p>
                <form method="POST" action="{{ route('cart.destroy', $item->product) }}" onsubmit="return confirm('Hapus item ini?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500 hover:underline text-xs">Hapus</button>
                </form>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-500 hover:underline">Kosongkan Keranjang</button>
            </form>
            <div class="text-right">
                <p class="text-sm text-gray-500">Subtotal</p>
                <p class="text-2xl font-bold text-gray-900">Rp{{ number_format($cart->subtotal(),0,',','.') }}</p>
            </div>
        </div>

        <a href="{{ route('checkout.index') }}" class="block text-center bg-orange-500 text-white py-3 rounded-lg mt-4 hover:bg-orange-600 font-medium">
            Lanjut ke Checkout
        </a>
    @endif
</div>
@endsection
