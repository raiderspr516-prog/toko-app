<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Provider Aktif
    |--------------------------------------------------------------------------
    |
    | "qris_manual" → aktif sekarang (lihat App\Services\Payment\QRISManualPaymentService)
    | "midtrans"    → skeleton, belum bisa dipakai sampai kredensial diisi
    |
    */
    'provider' => env('PAYMENT_PROVIDER', 'qris_manual'),

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    ],

    // Jangan pernah menaruh secret key di sini secara hard-code —
    // semua nilai wajib berasal dari .env.
];
