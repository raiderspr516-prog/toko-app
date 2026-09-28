<?php

use App\Http\Middleware\CheckAdminPermission;
use App\Http\Middleware\EnsureAdminIsActive;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'user.active' => EnsureUserIsActive::class,
            'admin.active' => EnsureAdminIsActive::class,
            'permission' => CheckAdminPermission::class,
        ]);

        $middleware->append(SecurityHeaders::class);

        // Guest yang mengakses route /admin/* diarahkan ke halaman login admin,
        // guest yang mengakses route customer diarahkan ke halaman login customer.
        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('admin/*')
                ? route('admin.login')
                : route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
