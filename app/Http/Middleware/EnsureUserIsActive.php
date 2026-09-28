<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memastikan akun customer yang login masih berstatus aktif.
 * Kalau admin menonaktifkan akun customer saat sesi masih berjalan,
 * customer langsung di-logout paksa di request berikutnya.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if ($user && ! $user->isActive()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw new AuthenticationException('Akun Anda telah dinonaktifkan. Silakan hubungi customer service.');
        }

        return $next($request);
    }
}
