@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran')
@section('content')
<form method="GET" class="mb-4">
    <select name="status" class="rounded-xl border-gray-300 text-sm" onchange="this.form.submit()">
        <option value="pending" @selected(request('status','pending')==='pending')>Menunggu Verifikasi</option>
        <option value="approved" @selected(request('status')==='approved')>Disetujui</option>
        <option value="rejected" @selected(request('status')==='rejected')>Ditolak</option>
    </select>
</form>

@if (session('success'))<div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>@endif
@if (session('error'))<div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>@endif

<div class="space-y-3">
    @forelse ($proofs as $proof)
    <div class="bg-white rounded-2xl shadow-sm p-4 flex items-center gap-4">
        <a href="{{ route('admin.payment-proofs.view', $proof) }}" target="_blank" class="w-20 h-20 bg-gray-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
            <span class="text-2xl">🧾</span>
        </a>
        <div class="flex-1">
            <p class="font-medium text-gray-800">{{ $proof->order->order_number }}</p>
            <p class="text-sm text-gray-500">{{ $proof->order->user->name }} &middot; Rp{{ number_format($proof->payment->amount,0,',','.') }}</p>
            <p class="text-xs text-gray-400">Diupload: {{ $proof->uploaded_at->format('d/m/Y H:i') }}</p>
            @if ($proof->status === 'rejected')
                <p class="text-xs text-red-600 mt-1">Ditolak: {{ $proof->admin_note }}</p>
            @endif
        </div>
        <a href="{{ route('admin.payment-proofs.view', $proof) }}" target="_blank" class="text-xs text-orange-600 hover:underline">Lihat Bukti</a>
        @if ($proof->status === 'pending')
        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.payment-verification.approve', $proof) }}">
                @csrf
                <button class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-700">Approve</button>
            </form>
            <form method="POST" action="{{ route('admin.payment-verification.reject', $proof) }}" onsubmit="return promptReject(event, this)">
                @csrf
                <input type="hidden" name="reason" class="reject-reason">
                <button class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700">Reject</button>
            </form>
        </div>
        @else
            <span class="px-2 py-1 rounded text-xs {{ $proof->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($proof->status) }}</span>
        @endif
    </div>
    @empty
    <p class="text-gray-400 text-center py-10">Tidak ada data.</p>
    @endforelse
</div>
<div class="mt-4">{{ $proofs->links() }}</div>

<script>
function promptReject(e, form) {
    const reason = prompt('Alasan penolakan pembayaran:');
    if (!reason) { e.preventDefault(); return false; }
    form.querySelector('.reject-reason').value = reason;
    return true;
}
</script>
@endsection
