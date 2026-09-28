@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Akun Baru</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-lg border-gray-300" required autofocus>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">No. HP (opsional)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>
            <button class="w-full bg-emerald-600 text-white py-2.5 rounded-lg hover:bg-emerald-700">Daftar</button>
        </form>

        <p class="text-sm text-gray-500 mt-6 text-center">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection
