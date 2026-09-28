@extends('layouts.app')
@section('title', 'Alamat Saya')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-800">Alamat Saya</h1>
        <a href="{{ route('addresses.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700">+ Tambah Alamat</a>
    </div>

    @if (session('success')) <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div> @endif

    <div class="space-y-3">
        @forelse ($addresses as $addr)
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center gap-2 mb-1">
                <p class="font-medium text-gray-800">{{ $addr->recipient_name }}</p>
                @if ($addr->is_default)
                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">Utama</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">{{ $addr->phone }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ $addr->fullText() }}</p>
            <form action="{{ route('addresses.destroy', $addr) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus alamat ini?')">
                @csrf @method('DELETE')
                <button class="text-xs text-red-600 hover:underline">Hapus</button>
            </form>
        </div>
        @empty
        <p class="text-gray-400 text-center py-10">Belum ada alamat tersimpan.</p>
        @endforelse
    </div>
</div>
@endsection
