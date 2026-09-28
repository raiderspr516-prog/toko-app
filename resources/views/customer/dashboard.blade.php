@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Halo, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 mb-6">Ini halaman akun kamu. Fitur belanja akan aktif bertahap di phase berikutnya.</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 text-sm">Logout</button>
    </form>
</div>
@endsection
