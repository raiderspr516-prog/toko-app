<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentWebhook;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Endpoint generik untuk menerima webhook payment gateway.
 * Aktif secara struktur, tapi implementasi verifikasi signature sesungguhnya
 * ada di masing-masing PaymentGatewayInterface::handleWebhook() (lihat
 * MidtransPaymentService — belum aktif karena kredensial belum ada).
 *
 * PRINSIP KEAMANAN yang tetap ditegakkan meski gateway belum aktif:
 *  - Payload mentah SELALU dicatat ke payment_webhooks sebelum diproses (audit).
 *  - Status pembayaran TIDAK PERNAH dipercaya dari payload tanpa validasi
 *    signature terlebih dahulu (didelegasikan ke gateway->handleWebhook()).
 *  - gateway_reference dipakai untuk idempotency (cegah proses webhook duplikat).
 */
class PaymentWebhookController extends Controller
{
    public function __construct(private PaymentGatewayInterface $gateway) {}

    public function handle(Request $request, string $gateway)
    {
        $payload = $request->all();

        $webhookLog = PaymentWebhook::create([
            'gateway' => $gateway,
            'event_type' => $payload['event_type'] ?? null,
            'raw_payload' => $payload,
            'signature' => $request->header('X-Signature') ?? $request->header('Signature'),
            'is_verified' => false,
        ]);

        try {
            $result = $this->gateway->handleWebhook($payload, $request->headers->all());
        } catch (\Throwable $e) {
            Log::warning('Payment webhook gagal diproses', ['gateway' => $gateway, 'error' => $e->getMessage()]);

            // Tetap balas 200 supaya gateway tidak retry terus-menerus untuk
            // provider yang memang belum diimplementasikan; payload tetap tersimpan untuk audit.
            return response()->json(['status' => 'logged', 'processed' => false], 200);
        }

        if (! $result->isValid) {
            $webhookLog->update(['processed_at' => now()]);

            return response()->json(['status' => 'invalid_signature'], 400);
        }

        $webhookLog->update(['is_verified' => true, 'processed_at' => now()]);

        // Idempotency: cari order dari gateway_reference, jangan proses dua kali
        // kalau payment sudah dalam status final.
        if ($result->gatewayReference) {
            $order = Order::whereHas('payment', fn ($q) => $q->where('gateway_reference', $result->gatewayReference))->first();

            if ($order && ! in_array($order->status, ['paid', 'completed', 'cancelled'], true)) {
                // Mapping status gateway → status order dilakukan di sini
                // saat provider sungguhan sudah diimplementasikan.
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
