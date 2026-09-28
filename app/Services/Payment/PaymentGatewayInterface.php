<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;

/**
 * Kontrak yang harus dipenuhi setiap provider pembayaran (QRIS manual,
 * Midtrans, Xendit, Tripay, dst). OrderController/CheckoutController TIDAK
 * PERNAH bicara langsung ke provider — selalu lewat PaymentService, yang
 * me-resolve implementasi ini berdasarkan konfigurasi aktif.
 */
interface PaymentGatewayInterface
{
    public function generatePayment(Order $order): PaymentResult;

    public function checkStatus(Payment $payment): string;

    public function handleWebhook(array $payload, array $headers): PaymentWebhookResult;
}
