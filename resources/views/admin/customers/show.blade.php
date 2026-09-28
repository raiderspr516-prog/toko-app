@extends('layouts.admin')
@section('title', 'Detail Customer')
@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <h3 class="font-semibold text-lg mb-3">{{ $customer->name }}</h3>
    <p class="text-sm text-gray-500">{{ $customer->email }} &middot; {{ $customer->phone ?: '-' }}</p>
    <p class="text-sm text-gray-500 mb-4">Terdaftar: {{ $customer->created_at->format('d/m/Y') }} &middot; Status: {{ ucfirst($customer->status) }}</p>

    <h4 class="font-semibold text-sm mb-2">10 Order Terakhir</h4>
    <table class="w-full text-sm">
        <thead class="text-left text-gray-500 border-b"><tr><th class="py-2">No. Order</th><th class="py-2">Total</th><th class="py-2">Status</th></tr></thead>
        <tbody>
            @forelse ($customer->orders as $o)
            <tr class="border-b last:border-0">
                <td class="py-2"><a href="{{ route('admin.orders.show', $o) }}" class="text-emerald-600 hover:underline">{{ $o->order_number }}</a></td>
                <td class="py-2">Rp{{ number_format($o->grand_total,0,',','.') }}</td>
                <td class="py-2">{{ $o->statusLabel() }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="py-4 text-center text-gray-400">Belum pernah order.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.customers.index') }}" class="inline-block mt-4 text-sm text-gray-500 hover:underline">&larr; Kembali</a>
</div>
@endsection
