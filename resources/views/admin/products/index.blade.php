@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama/SKU..." class="rounded-xl border-gray-300 text-sm">
        <select name="category_id" class="rounded-xl border-gray-300 text-sm" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-xl border-gray-300 text-sm" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status')==='draft')>Draft</option>
            <option value="active" @selected(request('status')==='active')>Aktif</option>
            <option value="inactive" @selected(request('status')==='inactive')>Nonaktif</option>
        </select>
        <button class="bg-gray-800 text-white px-3 py-1.5 rounded-lg text-sm">Cari</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-xl text-sm hover:bg-orange-600">+ Tambah Produk</a>
</div>

@if (session('success'))
    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr>
                <th class="px-4 py-3">Thumbnail</th>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">SKU</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Harga</th>
                <th class="px-4 py-3">Stok</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Featured</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr class="border-t">
                <td class="px-4 py-3">
                    @if ($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="w-10 h-10 object-cover rounded">
                    @else
                        <span class="text-2xl">📦</span>
                    @endif
                </td>
                <td class="px-4 py-3">{{ $product->name }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $product->sku }}</td>
                <td class="px-4 py-3">{{ $product->category->name }}</td>
                <td class="px-4 py-3">
                    @if ($product->discount_price)
                        <span class="line-through text-gray-400 text-xs">Rp{{ number_format($product->price,0,',','.') }}</span><br>
                        <span class="text-red-600 font-medium">Rp{{ number_format($product->discount_price,0,',','.') }}</span>
                    @else
                        Rp{{ number_format($product->price,0,',','.') }}
                    @endif
                </td>
                <td class="px-4 py-3">{{ $product->stock }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs
                        {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : ($product->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-600') }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </td>
                <td class="px-4 py-3">{{ $product->is_featured ? '⭐' : '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <a href="{{ route('admin.products.show', $product) }}" class="text-gray-600 hover:underline text-xs">Detail</a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-orange-600 hover:underline text-xs ml-2">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline text-xs ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="px-4 py-6 text-center text-gray-400">Belum ada produk.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
