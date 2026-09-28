@extends('layouts.app')
@section('title', 'Riwayat Pesanan')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-xl font-bold text-gray-800 mb-4">Riwayat Pesanan</h1>

    <form method="GET" class="mb-4">
        <select name="status" class="rounded-xl border-gray-300 text-sm" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach (['pending','waiting_payment','payment_review','paid','processing','shipped','completed','cancelled','expired'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
    </form>

    <div class="space-y-3">
        @forelse ($orders as $order)
        <a href="{{ route('orders.show', $order) }}" class="block bg-white rounded-2xl shadow-sm p-4 hover:shadow-md">
            <div class="flex items-center justify-between mb-1">
                <p class="font-semibold text-gray-800">{{ $order->order_number }}</p>
                <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">{{ $order->statusLabel() }}</span>
            </div>
            <p class="text-sm text-gray-500">{{ $order->items->count() }} item &middot; Rp{{ number_format($order->grand_total,0,',','.') }}</p>
            <p class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
        </a>
        @empty
        <p class="text-gray-400 text-center py-10">Belum ada pesanan.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
