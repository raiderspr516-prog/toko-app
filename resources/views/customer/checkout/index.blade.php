@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-xl font-bold text-gray-800 mb-4">Checkout</h1>

    @if (session('error')) <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div> @endif

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl shadow-sm p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-semibold text-gray-800">Alamat Pengiriman</h2>
                        <a href="{{ route('addresses.create') }}" class="text-xs text-orange-600 hover:underline">+ Tambah Alamat Baru</a>
                    </div>
                    @forelse ($addresses as $addr)
                        <label class="flex items-start gap-3 border rounded-lg p-3 mb-2 cursor-pointer hover:border-orange-500">
                            <input type="radio" name="address_id" value="{{ $addr->id }}" class="mt-1" {{ $addr->is_default ? 'checked' : '' }} required>
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">{{ $addr->recipient_name }} — {{ $addr->phone }}</p>
                                <p class="text-gray-500">{{ $addr->fullText() }}</p>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada alamat. <a href="{{ route('addresses.create') }}" class="text-orange-600 hover:underline">Tambah alamat</a> dulu sebelum checkout.</p>
                    @endforelse
                    @error('address_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-4">
                    <h2 class="font-semibold text-gray-800 mb-3">Kode Kupon (opsional)</h2>
                    <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="Masukkan kode kupon" class="w-full rounded-xl border-gray-300 text-sm">
                    @error('coupon_code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-4">
                    <h2 class="font-semibold text-gray-800 mb-3">Catatan Pesanan (opsional)</h2>
                    <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-300 text-sm">{{ old('notes') }}</textarea>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-4">
                    <h2 class="font-semibold text-gray-800 mb-3">Item Pesanan</h2>
                    @foreach ($cart->items as $item)
                        <div class="flex justify-between text-sm py-1">
                            <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                            <span>Rp{{ number_format($item->product->finalPrice() * $item->quantity,0,',','.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 h-fit sticky top-20">
                <h2 class="font-semibold text-gray-800 mb-3">Ringkasan Belanja</h2>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">Subtotal</span>
                    <span>Rp{{ number_format($cart->subtotal(),0,',','.') }}</span>
                </div>
                <p class="text-xs text-gray-400 mb-3">Ongkir & diskon dihitung ulang di server setelah kupon diverifikasi.</p>
                <button class="w-full bg-orange-500 text-white py-2.5 rounded-xl hover:bg-orange-600 font-medium">Buat Pesanan</button>
            </div>
        </div>
    </form>
</div>
@endsection
