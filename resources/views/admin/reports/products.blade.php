@extends('layouts.admin')
@section('title', 'Laporan Produk Terlaris')
@section('content')
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500"><tr><th class="px-4 py-3">Produk</th><th class="px-4 py-3">Terjual</th><th class="px-4 py-3">Total Pendapatan</th></tr></thead>
        <tbody>
            @forelse ($topProducts as $p)
            <tr class="border-t"><td class="px-4 py-3">{{ $p->product_name_snapshot }}</td><td class="px-4 py-3">{{ $p->total_qty }}</td><td class="px-4 py-3">Rp{{ number_format($p->total_revenue,0,',','.') }}</td></tr>
            @empty
            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
