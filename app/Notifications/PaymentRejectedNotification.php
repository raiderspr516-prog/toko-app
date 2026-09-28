<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification
{
    public function __construct(public Order $order, public string $reason) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pembayaran Ditolak',
            'message' => "Bukti pembayaran untuk pesanan {$this->order->order_number} ditolak: {$this->reason}. Silakan upload ulang.",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
        ];
    }
}
