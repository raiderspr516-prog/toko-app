@extends('layouts.admin')
@section('title', 'Detail Order')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-4">
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg">{{ $order->order_number }}</h3>
                <span class="px-3 py-1 rounded-full text-xs bg-gray-100">{{ $order->statusLabel() }}</span>
            </div>
            <p class="text-sm text-gray-500">Customer: {{ $order->user->name }} ({{ $order->user->email }})</p>
            <p class="text-sm text-gray-500">Alamat: {{ $order->address->fullText() }}, a.n {{ $order->address->recipient_name }} ({{ $order->address->phone }})</p>
            @if ($order->notes)<p class="text-sm text-gray-500">Catatan: {{ $order->notes }}</p>@endif
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h4 class="font-semibold mb-3">Item Pesanan</h4>
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500 border-b"><tr><th class="py-2">Produk</th><th class="py-2">Qty</th><th class="py-2">Harga</th><th class="py-2">Subtotal</th></tr></thead>
                <tbody>
                    @foreach ($order->items as $item)
                    <tr class="border-b last:border-0">
                        <td class="py-2">{{ $item->product_name_snapshot }}</td>
                        <td class="py-2">{{ $item->quantity }}</td>
                        <td class="py-2">Rp{{ number_format($item->product_price_snapshot,0,',','.') }}</td>
                        <td class="py-2">Rp{{ number_format($item->subtotal,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-sm mt-3 space-y-1 text-right">
                <p>Subtotal: Rp{{ number_format($order->subtotal,0,',','.') }}</p>
                <p>Ongkir: Rp{{ number_format($order->shipping_cost,0,',','.') }}</p>
                @if ($order->discount_amount)<p>Diskon: -Rp{{ number_format($order->discount_amount,0,',','.') }}</p>@endif
                <p class="font-bold text-base">Total: Rp{{ number_format($order->grand_total,0,',','.') }}</p>
            </div>
        </div>

        @if ($order->payment)
        <div class="bg-white rounded-xl shadow p-5">
            <h4 class="font-semibold mb-3">Pembayaran</h4>
            <p class="text-sm">Metode: {{ $order->payment->method }}</p>
            <p class="text-sm">Status: <span class="font-medium">{{ $order->payment->status }}</span></p>
            @if ($order->payment->proofs->isNotEmpty())
                @php $proof = $order->payment->proofs->sortByDesc('created_at')->first(); @endphp
                <p class="text-sm mt-2">Bukti bayar terakhir:</p>
                <a href="{{ route('admin.payment-proofs.view', $proof) }}" target="_blank" class="text-emerald-600 text-sm hover:underline">Lihat bukti pembayaran &rarr;</a>
                <p class="text-xs text-gray-400">Diupload: {{ $proof->created_at->format('d/m/Y H:i') }}</p>
            @endif
            <a href="{{ route('admin.payment-verification.index') }}" class="inline-block mt-3 text-xs text-emerald-600 hover:underline">Kelola di halaman Verifikasi Pembayaran &rarr;</a>
        </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow p-5">
            <h4 class="font-semibold mb-3">Ubah Status Order</h4>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf @method('PATCH')
                <select name="status" class="w-full rounded-lg border-gray-300 text-sm mb-3">
                    <option value="processing" @selected($order->status==='processing')>Processing</option>
                    <option value="completed" @selected($order->status==='completed')>Completed</option>
                    <option value="cancelled" @selected($order->status==='cancelled')>Cancelled</option>
                </select>
                <input type="text" name="cancelled_reason" placeholder="Alasan (jika dibatalkan)" class="w-full rounded-lg border-gray-300 text-sm mb-3">
                <button class="w-full bg-gray-900 text-white py-2 rounded-lg text-sm hover:bg-gray-800">Update Status</button>
            </form>
            <p class="text-xs text-gray-400 mt-2">Untuk status paid/shipped, gunakan halaman Verifikasi Pembayaran & Pengiriman.</p>
        </div>

        @if ($order->shipment)
        <div class="bg-white rounded-xl shadow p-5">
            <h4 class="font-semibold mb-2">Pengiriman</h4>
            <p class="text-sm">Kurir: {{ $order->shipment->courier ?? '-' }}</p>
            <p class="text-sm">Resi: {{ $order->shipment->tracking_number ?? '-' }}</p>
            <p class="text-sm">Status: {{ $order->shipment->status }}</p>
            @if ($order->status === 'shipped')
                <form method="POST" action="{{ route('admin.shipments.deliver', $order) }}" class="mt-3">
                    @csrf
                    <button class="w-full bg-emerald-600 text-white py-2 rounded-lg text-sm hover:bg-emerald-700">Tandai Selesai (Diterima Customer)</button>
                </form>
            @endif
        </div>
        @elseif (in_array($order->status, ['paid', 'processing']))
        <div class="bg-white rounded-xl shadow p-5">
            <h4 class="font-semibold mb-3">Input Pengiriman</h4>
            <form method="POST" action="{{ route('admin.shipments.update', $order) }}">
                @csrf
                <input type="text" name="courier" placeholder="Kurir (JNE, J&T, dst)" class="w-full rounded-lg border-gray-300 text-sm mb-2" required>
                <input type="text" name="tracking_number" placeholder="Nomor Resi" class="w-full rounded-lg border-gray-300 text-sm mb-3" required>
                <button class="w-full bg-gray-900 text-white py-2 rounded-lg text-sm hover:bg-gray-800">Tandai Dikirim</button>
            </form>
        </div>
        @endif

        <a href="{{ route('admin.orders.index') }}" class="block text-sm text-gray-500 hover:underline">&larr; Kembali ke daftar order</a>
    </div>
</div>
@endsection
