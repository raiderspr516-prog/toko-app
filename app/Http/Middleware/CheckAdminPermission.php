<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware permission granular untuk admin, contoh pemakaian di route:
 *   ->middleware('permission:products.create')
 *
 * Saat ini semua admin dibuat sebagai super admin (lihat AdminSeeder),
 * jadi middleware ini baru benar-benar membatasi ketika ada admin
 * non-super-admin dengan role terbatas (fitur role management menyusul
 * di Phase 15/16 sesuai kebutuhan).
 */
class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = $request->user('admin');

        if (! $admin || ! $admin->hasPermission($permission)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
