@csrf
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="mt-1 w-full rounded-xl border-gray-300" required>
    @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
</div>
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description" rows="3" class="mt-1 w-full rounded-xl border-gray-300">{{ old('description', $category->description ?? '') }}</textarea>
</div>
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Gambar/Icon</label>
    <input type="file" name="image" accept="image/*" class="mt-1 w-full">
    @isset($category->image)
        <img src="{{ asset('storage/'.$category->image) }}" class="h-16 mt-2 rounded">
    @endisset
</div>
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700">Status</label>
    <select name="status" class="mt-1 w-full rounded-xl border-gray-300">
        <option value="active" @selected(old('status', $category->status ?? 'active') === 'active')>Aktif</option>
        <option value="inactive" @selected(old('status', $category->status ?? '') === 'inactive')>Nonaktif</option>
    </select>
</div>
<div class="flex gap-3">
    <button class="bg-orange-500 text-white px-5 py-2 rounded-xl hover:bg-orange-600">Simpan</button>
    <a href="{{ route('admin.categories.index') }}" class="px-5 py-2 rounded-xl border border-gray-300 text-gray-700">Batal</a>
</div>
