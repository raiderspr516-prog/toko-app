@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">SKU</label>
        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
        @error('sku') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="category_id" class="mt-1 w-full rounded-lg border-gray-300" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1 w-full rounded-lg border-gray-300">
            @foreach (['draft' => 'Draft', 'active' => 'Aktif', 'inactive' => 'Nonaktif'] as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $product->status ?? 'draft') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" class="mt-1 w-full rounded-lg border-gray-300" required>
        @error('price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Harga Diskon (opsional)</label>
        <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price ?? '') }}" min="0" class="mt-1 w-full rounded-lg border-gray-300">
        @error('discount_price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Stok</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" class="mt-1 w-full rounded-lg border-gray-300" required>
        @error('stock') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Berat (gram)</label>
        <input type="number" name="weight" value="{{ old('weight', $product->weight ?? 0) }}" min="0" class="mt-1 w-full rounded-lg border-gray-300" required>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="description" rows="4" class="mt-1 w-full rounded-lg border-gray-300">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Gambar Utama</label>
        <input type="file" name="image" accept="image/*" class="mt-1 w-full">
        @isset($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="h-20 mt-2 rounded">
        @endisset
    </div>
    <div class="md:col-span-2 flex items-center gap-2">
        <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
        <label for="is_featured" class="text-sm text-gray-700">Produk Unggulan (tampil di beranda)</label>
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button class="bg-emerald-600 text-white px-5 py-2 rounded-lg hover:bg-emerald-700">Simpan</button>
    <a href="{{ route('admin.products.index') }}" class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700">Batal</a>
</div>
