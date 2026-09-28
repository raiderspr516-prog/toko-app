<?php

namespace App\Notifications;

use App\Models\PaymentProof;
use Illuminate\Notifications\Notification;

class NewPaymentProofAdminNotification extends Notification
{
    public function __construct(public PaymentProof $proof) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Bukti Pembayaran Baru',
            'message' => "Bukti pembayaran baru untuk order {$this->proof->order->order_number} menunggu verifikasi.",
            'order_id' => $this->proof->order_id,
        ];
    }
}
