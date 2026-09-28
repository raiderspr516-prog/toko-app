<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center font-sans">
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-sm">
        <h1 class="text-xl font-bold text-gray-800 mb-1">🛍️ {{ config('app.name') }}</h1>
        <p class="text-sm text-gray-500 mb-6">Admin Panel Login</p>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border-gray-300" required autofocus>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>
            <button class="w-full bg-gray-900 text-white py-2.5 rounded-lg hover:bg-gray-800">Masuk sebagai Admin</button>
        </form>
    </div>
</body>
</html>
