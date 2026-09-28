@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('content')
<form method="GET" class="mb-4">
    <select name="period" class="rounded-lg border-gray-300 text-sm" onchange="this.form.submit()">
        <option value="daily" @selected($period==='daily')>Harian</option>
        <option value="weekly" @selected($period==='weekly')>Mingguan</option>
        <option value="monthly" @selected($period==='monthly')>Bulanan</option>
        <option value="yearly" @selected($period==='yearly')>Tahunan</option>
    </select>
</form>
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500"><tr><th class="px-4 py-3">Periode</th><th class="px-4 py-3">Jumlah Order</th><th class="px-4 py-3">Total Pendapatan</th></tr></thead>
        <tbody>
            @forelse ($sales as $row)
            <tr class="border-t"><td class="px-4 py-3">{{ $row->period }}</td><td class="px-4 py-3">{{ $row->total_orders }}</td><td class="px-4 py-3">Rp{{ number_format($row->total_revenue,0,',','.') }}</td></tr>
            @empty
            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
