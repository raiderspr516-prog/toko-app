@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Masuk ke Akun Anda</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border-gray-300" required autofocus>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>
            <div class="mb-6 flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
            </div>
            <button class="w-full bg-emerald-600 text-white py-2.5 rounded-lg hover:bg-emerald-700">Masuk</button>
        </form>

        <p class="text-sm text-gray-500 mt-6 text-center">
            Belum punya akun? <a href="{{ route('register') }}" class="text-emerald-600 hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
