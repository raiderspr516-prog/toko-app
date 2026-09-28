@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden md:flex">
        <div class="gradient-brand md:w-2/5 p-8 text-white flex flex-col justify-center items-center text-center">
            <span class="text-6xl mb-3">🛍️</span>
            <h2 class="text-2xl font-extrabold mb-1">Selamat Datang Lagi!</h2>
            <p class="text-white/90 text-sm">Masuk dan lanjutkan belanja favoritmu di {{ config('app.name') }}.</p>
        </div>
        <div class="md:w-3/5 p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Masuk ke Akun Anda</h1>

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-pink-50 text-pink-700 px-4 py-3 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="mt-1 w-full rounded-xl border-gray-300 focus:border-orange-400 focus:ring-orange-400" required>
                </div>
                <div class="mb-6 flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="rounded text-orange-500">
                    <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                </div>
                <button class="w-full gradient-brand text-white font-semibold py-2.5 rounded-xl hover:opacity-90 shadow">Masuk</button>
            </form>

            <p class="text-sm text-gray-500 mt-6 text-center">
                Belum punya akun? <a href="{{ route('register') }}" class="text-orange-600 font-medium hover:underline">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection
