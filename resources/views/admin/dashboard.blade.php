@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-5"><p class="text-sm text-gray-500">Total Produk</p><p class="text-2xl font-bold">{{ $overview['total_products'] }}</p></div>
    <div class="bg-white rounded-xl shadow p-5"><p class="text-sm text-gray-500">Total Kategori</p><p class="text-2xl font-bold">{{ $overview['total_categories'] }}</p></div>
    <div class="bg-white rounded-xl shadow p-5"><p class="text-sm text-gray-500">Total Customer</p><p class="text-2xl font-bold">{{ $overview['total_customers'] }}</p></div>
    <div class="bg-white rounded-xl shadow p-5"><p class="text-sm text-gray-500">Total Order</p><p class="text-2xl font-bold">{{ $overview['total_orders'] }}</p></div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4"><p class="text-xs text-gray-500">Pesanan Baru</p><p class="text-xl font-bold text-blue-600">{{ $overview['new_orders'] }}</p></div>
    <div class="bg-white rounded-xl shadow p-4"><p class="text-xs text-gray-500">Menunggu Bayar</p><p class="text-xl font-bold text-yellow-600">{{ $overview['waiting_payment'] }}</p></div>
    <div class="bg-white rounded-xl shadow p-4"><p class="text-xs text-gray-500">Menunggu Verifikasi</p><p class="text-xl font-bold text-orange-600">{{ $overview['payment_review'] }}</p></div>
    <div class="bg-white rounded-xl shadow p-4"><p class="text-xs text-gray-500">Diproses</p><p class="text-xl font-bold text-indigo-600">{{ $overview['processing'] }}</p></div>
    <div class="bg-white rounded-xl shadow p-4"><p class="text-xs text-gray-500">Dikirim</p><p class="text-xl font-bold text-purple-600">{{ $overview['shipped'] }}</p></div>
    <div class="bg-white rounded-xl shadow p-4"><p class="text-xs text-gray-500">Selesai</p><p class="text-xl font-bold text-green-600">{{ $overview['completed'] }}</p></div>
</div>

<div class="bg-white rounded-xl shadow p-5 mb-6">
    <p class="text-sm text-gray-500">Total Pendapatan (order lunas ke atas)</p>
    <p class="text-3xl font-bold text-emerald-600">Rp{{ number_format($overview['total_revenue'],0,',','.') }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="md:col-span-2 bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold mb-3">Grafik Penjualan (14 Hari Terakhir)</h3>
        <canvas id="salesChart" height="100"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold mb-3">Produk Terlaris</h3>
        <table class="w-full text-sm">
            @forelse ($topProducts as $p)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $p->product_name_snapshot }}</td>
                <td class="py-2 text-right text-gray-500">{{ $p->total_qty }}x</td>
            </tr>
            @empty
            <tr><td class="py-4 text-center text-gray-400">Belum ada data.</td></tr>
            @endforelse
        </table>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-5">
    <h3 class="font-semibold mb-3">Pesanan Terbaru</h3>
    <table class="w-full text-sm">
        <thead class="text-left text-gray-500 border-b"><tr><th class="py-2">No. Order</th><th class="py-2">Customer</th><th class="py-2">Total</th><th class="py-2">Status</th></tr></thead>
        <tbody>
            @forelse ($recentOrders as $o)
            <tr class="border-b last:border-0">
                <td class="py-2"><a href="{{ route('admin.orders.show', $o) }}" class="text-emerald-600 hover:underline">{{ $o->order_number }}</a></td>
                <td class="py-2">{{ $o->user->name }}</td>
                <td class="py-2">Rp{{ number_format($o->grand_total,0,',','.') }}</td>
                <td class="py-2">{{ $o->statusLabel() }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="py-4 text-center text-gray-400">Belum ada order.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: @json($salesChart['labels']),
        datasets: [{
            label: 'Penjualan (Rp)',
            data: @json($salesChart['data']),
            borderColor: '#059669',
            backgroundColor: 'rgba(5,150,105,0.1)',
            tension: 0.3,
            fill: true,
        }]
    },
    options: { plugins: { legend: { display: false } } }
});
</script>
@endsection
