@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content')
<div class="max-w-lg mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 text-center">
        <h1 class="text-lg font-bold text-gray-800 mb-1">Pembayaran Order {{ $order->order_number }}</h1>
        <p class="text-2xl font-bold text-orange-600 mb-4">Rp{{ number_format($order->grand_total,0,',','.') }}</p>

        @if (session('success'))<div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm text-left">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm text-left">{{ session('error') }}</div>@endif

        @if ($order->status === 'payment_review')
            <div class="bg-yellow-50 text-yellow-800 rounded-lg p-4 text-sm mb-4">
                ⏳ Bukti pembayaran kamu sedang diverifikasi admin. Mohon ditunggu.
            </div>
        @elseif ($order->status === 'paid' || $order->status === 'processing' || $order->status === 'completed' || $order->status === 'shipped')
            <div class="bg-green-50 text-green-800 rounded-lg p-4 text-sm mb-4">
                ✅ Pembayaran sudah dikonfirmasi. Terima kasih!
            </div>
        @elseif ($gatewayResult->success)
            <img src="{{ $gatewayResult->qrisImageUrl }}" alt="QRIS" class="w-56 h-56 object-contain mx-auto border rounded-lg p-2 mb-3">
            <p class="text-sm text-gray-500 mb-4">{{ $gatewayResult->message }}</p>

            @if ($order->payment && $order->payment->latestProof && $order->payment->latestProof->status === 'rejected')
                <div class="bg-red-50 text-red-700 rounded-lg p-3 text-sm text-left mb-4">
                    Bukti sebelumnya ditolak: {{ $order->payment->latestProof->admin_note }}. Silakan upload ulang.
                </div>
            @endif

            <form method="POST" action="{{ route('payment-proof.store', $order) }}" enctype="multipart/form-data" class="text-left">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pembayaran</label>
                <input type="file" name="proof" accept="image/jpeg,image/png,image/webp" class="w-full mb-2" required>
                @error('proof') <p class="text-red-600 text-xs mb-2">{{ $message }}</p> @enderror
                <button class="w-full bg-orange-500 text-white py-2.5 rounded-xl hover:bg-orange-600">Upload Bukti Bayar</button>
            </form>
        @else
            <div class="bg-red-50 text-red-700 rounded-lg p-4 text-sm">{{ $gatewayResult->message }}</div>
        @endif

        <a href="{{ route('orders.show', $order) }}" class="block mt-6 text-sm text-gray-500 hover:underline">&larr; Lihat detail order</a>
    </div>
</div>
@endsection
