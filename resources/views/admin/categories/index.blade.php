@extends('layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h2 class="font-semibold text-gray-800">Daftar Kategori</h2>
    <a href="{{ route('admin.categories.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-xl text-sm hover:bg-orange-600">+ Tambah Kategori</a>
</div>

@if (session('success'))
    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr>
                <th class="px-4 py-3">Gambar</th>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Slug</th>
                <th class="px-4 py-3">Jumlah Produk</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
            <tr class="border-t">
                <td class="px-4 py-3">
                    @if ($category->image)
                        <img src="{{ asset('storage/'.$category->image) }}" class="w-10 h-10 object-cover rounded">
                    @else
                        <span class="text-2xl">🗂️</span>
                    @endif
                </td>
                <td class="px-4 py-3">{{ $category->name }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $category->slug }}</td>
                <td class="px-4 py-3">{{ $category->products_count }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs {{ $category->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                        {{ ucfirst($category->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-orange-600 hover:underline text-xs">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline text-xs ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $categories->links() }}</div>
@endsection
