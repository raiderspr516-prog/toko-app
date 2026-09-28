@extends('layouts.admin')
@section('title', 'Laporan Customer')
@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500"><tr><th class="px-4 py-3">Customer</th><th class="px-4 py-3">Jumlah Order</th><th class="px-4 py-3">Total Belanja</th></tr></thead>
        <tbody>
            @forelse ($topCustomers as $c)
            <tr class="border-t"><td class="px-4 py-3">{{ $c->user->name }}</td><td class="px-4 py-3">{{ $c->total_orders }}</td><td class="px-4 py-3">Rp{{ number_format($c->total_spent,0,',','.') }}</td></tr>
            @empty
            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
