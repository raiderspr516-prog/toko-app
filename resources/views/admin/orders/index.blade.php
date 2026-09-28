@extends('layouts.admin')
@section('title', 'Pesanan')
@section('content')
<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari no. order..." class="rounded-lg border-gray-300 text-sm">
    <select name="status" class="rounded-lg border-gray-300 text-sm" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        @foreach (['pending','waiting_payment','payment_review','paid','processing','shipped','completed','cancelled','expired'] as $s)
            <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    <button class="bg-gray-800 text-white px-3 py-1.5 rounded-lg text-sm">Cari</button>
</form>

@if (session('success'))<div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>@endif

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">No. Order</th><th class="px-4 py-3">Customer</th><th class="px-4 py-3">Item</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            <tr class="border-t">
                <td class="px-4 py-3">{{ $order->order_number }}</td>
                <td class="px-4 py-3">{{ $order->user->name }}</td>
                <td class="px-4 py-3">{{ $order->items->count() }}</td>
                <td class="px-4 py-3">Rp{{ number_format($order->grand_total,0,',','.') }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-gray-100">{{ $order->statusLabel() }}</span></td>
                <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="text-emerald-600 hover:underline text-xs">Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada order.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
