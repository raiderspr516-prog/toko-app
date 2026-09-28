@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden md:flex">
        <div class="gradient-brand md:w-2/5 p-8 text-white flex flex-col justify-center items-center text-center order-2 md:order-1">
            <span class="text-6xl mb-3">🎉</span>
            <h2 class="text-2xl font-extrabold mb-1">Gabung Sekarang!</h2>
            <p class="text-white/90 text-sm">Daftar gratis dan nikmati promo eksklusif setiap hari.</p>
        </div>
        <div class="md:w-3/5 p-8 order-1 md:order-2">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Akun Baru</h1>

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-pink-50 text-pink-700 px-4 py-3 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">No. HP (opsional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="mt-1 w-full rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 w-full rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400" required>
                </div>
                <button class="w-full gradient-brand text-white font-semibold py-2.5 rounded-xl hover:opacity-90 shadow">Daftar Sekarang</button>
            </form>

            <p class="text-sm text-gray-500 mt-6 text-center">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-orange-600 font-medium hover:underline">Masuk</a>
            </p>
        </div>
    </div>
</div>
@endsection
