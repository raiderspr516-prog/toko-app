@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-800">{{ $order->order_number }}</h1>
        <span class="px-3 py-1 rounded-full text-xs bg-gray-100">{{ $order->statusLabel() }}</span>
    </div>

    @if (session('success'))<div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>@endif

    {{-- Timeline --}}
    @php
        $steps = ['pending' => 'Order Dibuat', 'paid' => 'Pembayaran Dikonfirmasi', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai'];
        $order_progress = ['pending','waiting_payment','payment_review','paid','processing','shipped','completed'];
        $currentIndex = array_search($order->status, $order_progress);
    @endphp
    @if (!in_array($order->status, ['cancelled', 'expired']))
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-4">
        <div class="flex justify-between text-xs text-gray-500">
            @foreach ($steps as $key => $label)
                @php $stepIndex = array_search($key, $order_progress); @endphp
                <div class="flex-1 text-center {{ $currentIndex >= $stepIndex ? 'text-orange-600 font-medium' : '' }}">
                    <div class="w-3 h-3 rounded-full mx-auto mb-1 {{ $currentIndex >= $stepIndex ? 'bg-orange-500' : 'bg-gray-300' }}"></div>
                    {{ $label }}
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-red-50 text-red-700 rounded-xl p-4 mb-4 text-sm">
        Order ini {{ $order->status === 'cancelled' ? 'dibatalkan' : 'kedaluwarsa' }}.
        @if ($order->cancelled_reason) Alasan: {{ $order->cancelled_reason }} @endif
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-5 mb-4">
        <h3 class="font-semibold mb-2">Item Pesanan</h3>
        @foreach ($order->items as $item)
        <div class="flex justify-between text-sm py-1">
            <span>{{ $item->product_name_snapshot }} x{{ $item->quantity }}</span>
            <span>Rp{{ number_format($item->subtotal,0,',','.') }}</span>
        </div>
        @endforeach
        <div class="text-sm mt-3 pt-3 border-t space-y-1">
            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>Rp{{ number_format($order->subtotal,0,',','.') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Ongkir</span><span>Rp{{ number_format($order->shipping_cost,0,',','.') }}</span></div>
            @if ($order->discount_amount)
            <div class="flex justify-between text-orange-600"><span>Diskon</span><span>-Rp{{ number_format($order->discount_amount,0,',','.') }}</span></div>
            @endif
            <div class="flex justify-between font-bold text-base"><span>Total</span><span>Rp{{ number_format($order->grand_total,0,',','.') }}</span></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 mb-4">
        <h3 class="font-semibold mb-2">Alamat Pengiriman</h3>
        <p class="text-sm text-gray-600">{{ $order->address->recipient_name }} — {{ $order->address->phone }}</p>
        <p class="text-sm text-gray-600">{{ $order->address->fullText() }}</p>
    </div>

    @if ($order->shipment && $order->shipment->tracking_number)
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-4">
        <h3 class="font-semibold mb-2">Info Pengiriman</h3>
        <p class="text-sm">Kurir: {{ $order->shipment->courier }}</p>
        <p class="text-sm">No. Resi: {{ $order->shipment->tracking_number }}</p>
    </div>
    @endif

    <div class="flex gap-3">
        @if (in_array($order->status, ['pending', 'waiting_payment']))
            <a href="{{ route('checkout.payment', $order) }}" class="flex-1 text-center bg-orange-500 text-white py-2.5 rounded-xl hover:bg-orange-600 text-sm font-medium">Bayar Sekarang</a>
        @endif
        @if ($order->isCancellableByCustomer())
            <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Batalkan pesanan ini?')" class="flex-1">
                @csrf
                <button class="w-full border border-red-500 text-red-600 py-2.5 rounded-xl hover:bg-red-50 text-sm">Batalkan Pesanan</button>
            </form>
        @endif
    </div>

    <a href="{{ route('orders.index') }}" class="block mt-4 text-sm text-gray-500 hover:underline">&larr; Kembali ke riwayat pesanan</a>
</div>
@endsection
