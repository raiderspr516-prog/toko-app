@extends('layouts.app')
@section('title', 'Tambah Alamat')
@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-4">Tambah Alamat Baru</h1>
        <form method="POST" action="{{ route('addresses.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                    <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province') }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                    <input type="text" name="district" value="{{ old('district') }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="mt-1 w-full rounded-xl border-gray-300" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea name="full_address" rows="3" class="mt-1 w-full rounded-xl border-gray-300" required>{{ old('full_address') }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" class="mt-1 w-full rounded-xl border-gray-300">
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_default" value="1" id="is_default">
                    <label for="is_default" class="text-sm text-gray-600">Jadikan alamat utama</label>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button class="bg-orange-500 text-white px-5 py-2 rounded-xl hover:bg-orange-600">Simpan Alamat</button>
                <a href="{{ url()->previous() }}" class="px-5 py-2 rounded-xl border border-gray-300 text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
