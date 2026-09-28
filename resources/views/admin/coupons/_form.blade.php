@csrf
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm text-gray-700">Kode Kupon</label>
        <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" class="mt-1 w-full rounded-lg border-gray-300 uppercase" required>
        @error('code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-gray-700">Tipe</label>
        <select name="type" class="mt-1 w-full rounded-lg border-gray-300">
            <option value="percentage" @selected(old('type', $coupon->type ?? '')==='percentage')>Persentase (%)</option>
            <option value="fixed" @selected(old('type', $coupon->type ?? '')==='fixed')>Nominal (Rp)</option>
        </select>
    </div>
    <div>
        <label class="block text-sm text-gray-700">Nilai</label>
        <input type="number" name="value" value="{{ old('value', $coupon->value ?? '') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
        @error('value') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-gray-700">Maks. Diskon (opsional, khusus persen)</label>
        <input type="number" name="maximum_discount" value="{{ old('maximum_discount', $coupon->maximum_discount ?? '') }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-sm text-gray-700">Minimal Belanja</label>
        <input type="number" name="minimum_purchase" value="{{ old('minimum_purchase', $coupon->minimum_purchase ?? 0) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-sm text-gray-700">Batas Pemakaian (opsional)</label>
        <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-sm text-gray-700">Tanggal Mulai</label>
        <input type="date" name="start_date" value="{{ old('start_date', isset($coupon->start_date) ? $coupon->start_date->format('Y-m-d') : '') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm text-gray-700">Tanggal Berakhir</label>
        <input type="date" name="end_date" value="{{ old('end_date', isset($coupon->end_date) ? $coupon->end_date->format('Y-m-d') : '') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
    </div>
    <div class="col-span-2">
        <label class="block text-sm text-gray-700">Status</label>
        <select name="status" class="mt-1 w-full rounded-lg border-gray-300">
            <option value="active" @selected(old('status', $coupon->status ?? 'active')==='active')>Aktif</option>
            <option value="inactive" @selected(old('status', $coupon->status ?? '')==='inactive')>Nonaktif</option>
        </select>
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button class="bg-emerald-600 text-white px-5 py-2 rounded-lg hover:bg-emerald-700">Simpan</button>
    <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700">Batal</a>
</div>
