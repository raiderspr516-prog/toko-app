<?php

use App\Http\Controllers\Api\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Prefix otomatis "/api". Saat ini hanya berisi endpoint webhook payment
| (struktur siap, provider aktif masih qris_manual — lihat
| PaymentWebhookController untuk detail). REST API publik (mobile app dsb)
| bisa ditambahkan di sini nanti tanpa mengubah struktur web routes.
|
*/

Route::post('/webhooks/payment/{gateway}', [PaymentWebhookController::class, 'handle'])
    ->name('api.webhooks.payment')
    ->middleware('throttle:60,1');
