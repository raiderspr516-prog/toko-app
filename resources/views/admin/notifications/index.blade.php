@extends('layouts.admin')
@section('title', 'Notifikasi')
@section('content')
<div class="flex items-center justify-between mb-4">
    <h2 class="font-semibold text-gray-800">Notifikasi</h2>
    <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}">
        @csrf
        <button class="text-sm text-orange-600 hover:underline">Tandai semua dibaca</button>
    </form>
</div>
<div class="space-y-2">
    @forelse ($notifications as $notif)
    <div class="bg-white rounded-2xl shadow-sm p-4 flex items-start justify-between {{ $notif->read_at ? 'opacity-60' : '' }}">
        <div>
            <p class="font-medium text-gray-800">{{ $notif->data['title'] }}</p>
            <p class="text-sm text-gray-500">{{ $notif->data['message'] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
        </div>
        @if (!$notif->read_at)
        <form method="POST" action="{{ route('admin.notifications.mark-read', $notif->id) }}">
            @csrf
            <button class="text-xs text-orange-600 hover:underline">Tandai dibaca</button>
        </form>
        @endif
    </div>
    @empty
    <p class="text-gray-400 text-center py-10">Belum ada notifikasi.</p>
    @endforelse
</div>
<div class="mt-4">{{ $notifications->links() }}</div>
@endsection
