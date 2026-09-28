@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('content')
@if (session('success'))<div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>@endif

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="max-w-2xl space-y-4">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold mb-3">Info Toko</h3>
        <div class="space-y-3">
            <div><label class="block text-sm text-gray-700">Nama Toko</label><input type="text" name="store_name" value="{{ old('store_name', $settings['store_name']) }}" class="mt-1 w-full rounded-lg border-gray-300"></div>
            <div><label class="block text-sm text-gray-700">Deskripsi</label><textarea name="store_description" class="mt-1 w-full rounded-lg border-gray-300">{{ old('store_description', $settings['store_description']) }}</textarea></div>
            <div><label class="block text-sm text-gray-700">No. Telepon</label><input type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone']) }}" class="mt-1 w-full rounded-lg border-gray-300"></div>
            <div><label class="block text-sm text-gray-700">Email</label><input type="email" name="store_email" value="{{ old('store_email', $settings['store_email']) }}" class="mt-1 w-full rounded-lg border-gray-300"></div>
            <div><label class="block text-sm text-gray-700">Alamat</label><textarea name="store_address" class="mt-1 w-full rounded-lg border-gray-300">{{ old('store_address', $settings['store_address']) }}</textarea></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold mb-3">Pembayaran — QRIS & Rekening</h3>
        <p class="text-xs text-gray-500 mb-3">
            Mode aktif saat ini: <strong>QRIS Manual</strong> (verifikasi oleh admin). Upload gambar QRIS statis toko di bawah ini.
        </p>
        <div class="space-y-3">
            <div>
                <label class="block text-sm text-gray-700">Gambar QRIS</label>
                <input type="file" name="qris_image" accept="image/*" class="mt-1 w-full">
                @if ($settings['qris_image_path'])
                    <img src="{{ asset('storage/'.$settings['qris_image_path']) }}" class="h-32 mt-2 rounded border">
                @else
                    <p class="text-xs text-red-500 mt-1">Belum ada gambar QRIS diupload — customer tidak akan bisa membayar sampai ini diisi.</p>
                @endif
            </div>
            <div><label class="block text-sm text-gray-700">Nama Bank</label><input type="text" name="bank_name" value="{{ old('bank_name', $settings['bank_name']) }}" class="mt-1 w-full rounded-lg border-gray-300"></div>
            <div><label class="block text-sm text-gray-700">Nama Pemilik Rekening</label><input type="text" name="bank_account_name" value="{{ old('bank_account_name', $settings['bank_account_name']) }}" class="mt-1 w-full rounded-lg border-gray-300"></div>
            <div><label class="block text-sm text-gray-700">Nomor Rekening</label><input type="text" name="bank_account_number" value="{{ old('bank_account_number', $settings['bank_account_number']) }}" class="mt-1 w-full rounded-lg border-gray-300"></div>
        </div>
    </div>

    <button class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700">Simpan Pengaturan</button>
</form>
@endsection
