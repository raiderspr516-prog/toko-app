@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-6">
        <aside class="w-full md:w-64 flex-shrink-0">
            <form method="GET" class="bg-white rounded-xl shadow p-4 space-y-4">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari produk..." class="w-full rounded-lg border-gray-300 text-sm">

                <div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Kategori</p>
                    <select name="category" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Rentang Harga</p>
                    <div class="flex gap-2">
                        <input type="number" name="harga_min" value="{{ request('harga_min') }}" placeholder="Min" class="w-1/2 rounded-lg border-gray-300 text-sm">
                        <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="Max" class="w-1/2 rounded-lg border-gray-300 text-sm">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="stok_tersedia" value="1" id="stok" {{ request('stok_tersedia') ? 'checked' : '' }}>
                    <label for="stok" class="text-sm text-gray-600">Hanya yang tersedia</label>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Urutkan</p>
                    <select name="urutkan" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="terbaru" @selected(request('urutkan')==='terbaru')>Terbaru</option>
                        <option value="harga_asc" @selected(request('urutkan')==='harga_asc')>Harga Terendah</option>
                        <option value="harga_desc" @selected(request('urutkan')==='harga_desc')>Harga Tertinggi</option>
                        <option value="terlaris" @selected(request('urutkan')==='terlaris')>Terlaris</option>
                    </select>
                </div>

                <button class="w-full bg-emerald-600 text-white py-2 rounded-lg text-sm hover:bg-emerald-700">Terapkan</button>
                <a href="{{ route('products.index') }}" class="block text-center text-sm text-gray-500 hover:underline">Reset filter</a>
            </form>
        </aside>

        <div class="flex-1">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($products as $product)
                    @include('customer.products._card')
                @empty
                    <p class="col-span-4 text-center text-gray-400 py-10">Tidak ada produk yang cocok.</p>
                @endforelse
            </div>
            <div class="mt-6">{{ $products->links() }}</div>
        </div>
    </div>
</div>
@endsection
