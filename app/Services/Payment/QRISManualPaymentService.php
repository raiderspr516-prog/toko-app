<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;

/**
 * Implementasi AKTIF saat ini: QRIS statis (gambar diupload admin lewat
 * Settings) + verifikasi manual oleh admin lewat upload bukti bayar.
 * TIDAK ada koneksi ke API eksternal — ini BUKAN simulasi/mock, seluruh
 * alur (order dibuat → QRIS ditampilkan → bukti diupload → admin verifikasi
 * → status berubah nyata di database) berjalan penuh. Yang tidak ada hanyalah
 * auto-verifikasi real-time, karena itu butuh kredensial provider sungguhan
 * yang belum tersedia (lihat MidtransPaymentService sebagai contoh nanti).
 */
class QRISManualPaymentService implements PaymentGatewayInterface
{
    public function generatePayment(Order $order): PaymentResult
    {
        $qrisImage = Setting::get('qris_image_path');

        if (! $qrisImage) {
            return new PaymentResult(
                success: false,
                message: 'Gambar QRIS belum diunggah oleh admin. Silakan hubungi customer service, atau admin dapat mengunggahnya di menu Pengaturan.'
            );
        }

        return new PaymentResult(
            success: true,
            qrisImageUrl: asset('storage/'.$qrisImage),
            gatewayReference: null, // manual, tidak ada reference dari provider
            message: 'Silakan scan QRIS dan upload bukti pembayaran setelah transfer.'
        );
    }

    public function checkStatus(Payment $payment): string
    {
        // Tidak ada status real-time dari provider — status ditentukan
        // sepenuhnya oleh alur upload bukti + verifikasi admin (Phase 10/11).
        return $payment->status;
    }

    public function handleWebhook(array $payload, array $headers): PaymentWebhookResult
    {
        // QRIS manual tidak punya webhook. Method ini ada untuk memenuhi
        // interface (Liskov substitution) — dipakai kalau nanti pindah ke
        // gateway yang punya webhook, tanpa mengubah kode pemanggilnya.
        return new PaymentWebhookResult(isValid: false, message: 'QRIS manual tidak menerima webhook.');
    }
}
