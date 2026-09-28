@extends('layouts.admin')
@section('title', 'Detail Produk')
@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <h3 class="font-semibold text-lg mb-4">{{ $product->name }}</h3>
    <table class="w-full text-sm mb-6">
        <tr class="border-b"><td class="py-2 text-gray-500 w-48">SKU</td><td class="py-2">{{ $product->sku }}</td></tr>
        <tr class="border-b"><td class="py-2 text-gray-500">Kategori</td><td class="py-2">{{ $product->category->name }}</td></tr>
        <tr class="border-b"><td class="py-2 text-gray-500">Harga</td><td class="py-2">Rp{{ number_format($product->price,0,',','.') }}</td></tr>
        <tr class="border-b"><td class="py-2 text-gray-500">Harga Diskon</td><td class="py-2">{{ $product->discount_price ? 'Rp'.number_format($product->discount_price,0,',','.') : '-' }}</td></tr>
        <tr class="border-b"><td class="py-2 text-gray-500">Stok Saat Ini</td><td class="py-2 font-semibold">{{ $product->stock }}</td></tr>
        <tr class="border-b"><td class="py-2 text-gray-500">Status</td><td class="py-2">{{ ucfirst($product->status) }}</td></tr>
        <tr><td class="py-2 text-gray-500">Deskripsi</td><td class="py-2">{{ $product->description ?: '-' }}</td></tr>
    </table>

    <h4 class="font-semibold text-sm text-gray-700 mb-2">Riwayat Pergerakan Stok</h4>
    <table class="w-full text-sm mb-4">
        <thead class="text-left text-gray-500 border-b">
            <tr><th class="py-2">Tanggal</th><th class="py-2">Tipe</th><th class="py-2">Qty</th><th class="py-2">Catatan</th></tr>
        </thead>
        <tbody>
            @forelse ($product->inventoryMovements->sortByDesc('created_at') as $mv)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $mv->created_at->format('d/m/Y H:i') }}</td>
                <td class="py-2">{{ $mv->type }}</td>
                <td class="py-2 {{ $mv->quantity < 0 ? 'text-red-600' : 'text-green-600' }}">{{ $mv->quantity > 0 ? '+' : '' }}{{ $mv->quantity }}</td>
                <td class="py-2 text-gray-500">{{ $mv->note }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="py-4 text-center text-gray-400">Belum ada pergerakan stok.</td></tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Kembali</a>
</div>
@endsection
