@extends('layouts.admin')
@section('title', 'Customer')
@section('content')
<form method="GET" class="mb-4"><input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama/email..." class="rounded-lg border-gray-300 text-sm"></form>
@if (session('success'))<div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>@endif
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500"><tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">HP</th><th class="px-4 py-3">Jml Order</th><th class="px-4 py-3">Total Transaksi</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Terdaftar</th><th class="px-4 py-3">Aksi</th></tr></thead>
        <tbody>
            @forelse ($customers as $c)
            <tr class="border-t">
                <td class="px-4 py-3"><a href="{{ route('admin.customers.show', $c) }}" class="text-emerald-600 hover:underline">{{ $c->name }}</a></td>
                <td class="px-4 py-3">{{ $c->email }}</td>
                <td class="px-4 py-3">{{ $c->phone ?: '-' }}</td>
                <td class="px-4 py-3">{{ $c->orders_count }}</td>
                <td class="px-4 py-3">Rp{{ number_format($c->total_spent ?? 0,0,',','.') }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs {{ $c->status==='active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">{{ ucfirst($c->status) }}</span></td>
                <td class="px-4 py-3">{{ $c->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <form method="POST" action="{{ route('admin.customers.toggle-status', $c) }}" onsubmit="return confirm('Ubah status akun ini?')">
                        @csrf
                        <button class="text-xs {{ $c->status==='active' ? 'text-red-600' : 'text-emerald-600' }} hover:underline">{{ $c->status==='active' ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-6 text-center text-gray-400">Belum ada customer.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $customers->links() }}</div>
@endsection
