@extends('layouts.admin')
@section('title', 'Kupon')
@section('content')
<div class="flex items-center justify-between mb-4">
    <h2 class="font-semibold text-gray-800">Daftar Kupon</h2>
    <a href="{{ route('admin.coupons.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-xl text-sm hover:bg-orange-600">+ Tambah Kupon</a>
</div>
@if (session('success'))<div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>@endif
<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">Kode</th><th class="px-4 py-3">Tipe</th><th class="px-4 py-3">Nilai</th><th class="px-4 py-3">Periode</th><th class="px-4 py-3">Terpakai</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($coupons as $c)
            <tr class="border-t">
                <td class="px-4 py-3 font-mono">{{ $c->code }}</td>
                <td class="px-4 py-3">{{ $c->type === 'percentage' ? 'Persen' : 'Nominal' }}</td>
                <td class="px-4 py-3">{{ $c->type === 'percentage' ? $c->value.'%' : 'Rp'.number_format($c->value,0,',','.') }}</td>
                <td class="px-4 py-3">{{ $c->start_date->format('d/m/Y') }} - {{ $c->end_date->format('d/m/Y') }}</td>
                <td class="px-4 py-3">{{ $c->used_count }}{{ $c->usage_limit ? '/'.$c->usage_limit : '' }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs {{ $c->status==='active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">{{ ucfirst($c->status) }}</span></td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <a href="{{ route('admin.coupons.edit', $c) }}" class="text-orange-600 hover:underline text-xs">Edit</a>
                    <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kupon ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline text-xs ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada kupon.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $coupons->links() }}</div>
@endsection
