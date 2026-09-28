<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;

/**
 * Satu-satunya pintu masuk untuk urusan pembayaran. Controller/Service lain
 * TIDAK PERNAH memanggil QRISManualPaymentService atau MidtransPaymentService
 * secara langsung — selalu lewat class ini, supaya provider bisa diganti
 * cukup lewat konfigurasi (config('payment.provider') / .env PAYMENT_PROVIDER)
 * tanpa mengubah kode checkout/order sama sekali.
 */
class PaymentService
{
    public function __construct(private PaymentGatewayInterface $gateway) {}

    public function createForOrder(Order $order): Payment
    {
        return DB::transaction(function () use ($order) {
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'method' => config('payment.provider', 'qris_manual'),
                    'amount' => $order->grand_total,
                    'status' => 'pending',
                    'expired_at' => now()->addHours(24),
                ]
            );

            $result = $this->gateway->generatePayment($order);

            PaymentTransaction::create([
                'payment_id' => $payment->id,
                'gateway' => config('payment.provider', 'qris_manual'),
                'request_payload' => ['order_number' => $order->order_number, 'amount' => $order->grand_total],
                'response_payload' => [
                    'success' => $result->success,
                    'qris_image_url' => $result->qrisImageUrl,
                    'message' => $result->message,
                ],
                'status' => $result->success ? 'generated' : 'failed',
            ]);

            if ($result->success) {
                $payment->update([
                    'status' => 'waiting_review' === $payment->status ? $payment->status : 'pending',
                    'gateway_reference' => $result->gatewayReference,
                ]);
                $order->update(['status' => 'waiting_payment']);
            }

            $payment->refresh();
            $payment->setAttribute('_gateway_result', $result); // dipakai controller untuk tampilkan QRIS, tidak disimpan ke DB

            return $payment;
        });
    }

    public function getGatewayResultFor(Order $order): PaymentResult
    {
        return $this->gateway->generatePayment($order);
    }
}
