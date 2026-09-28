<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;

/**
 * SKELETON — belum aktif. Kredensial Midtrans (server key, client key)
 * belum tersedia di aplikasi ini (lihat PAYMENT_PROVIDER di .env, saat ini
 * di-set ke "qris_manual"). Jangan aktifkan class ini di PaymentService
 * sebelum kredensial asli tersedia dan sudah diuji di environment sandbox.
 *
 * Implementasi nyata nanti akan:
 *  - generatePayment(): panggil Midtrans Snap API untuk buat transaksi QRIS,
 *    simpan snap_token/qr_string, dan catat request/response ke payment_transactions.
 *  - checkStatus(): panggil Midtrans Get Status API.
 *  - handleWebhook(): validasi signature_key (SHA512 dari order_id+status_code+
 *    gross_amount+server_key) sebelum memproses notifikasi.
 */
class MidtransPaymentService implements PaymentGatewayInterface
{
    public function generatePayment(Order $order): PaymentResult
    {
        throw new \RuntimeException(
            'MidtransPaymentService belum dikonfigurasi. Set kredensial MIDTRANS_SERVER_KEY '.
            'dan MIDTRANS_CLIENT_KEY di .env, lalu implementasikan pemanggilan Snap API di sini.'
        );
    }

    public function checkStatus(Payment $payment): string
    {
        throw new \RuntimeException('MidtransPaymentService::checkStatus() belum diimplementasikan.');
    }

    public function handleWebhook(array $payload, array $headers): PaymentWebhookResult
    {
        throw new \RuntimeException('MidtransPaymentService::handleWebhook() belum diimplementasikan.');
    }
}
