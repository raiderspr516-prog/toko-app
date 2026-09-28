<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pembayaran Diterima',
            'message' => "Pembayaran untuk pesanan {$this->order->order_number} telah dikonfirmasi. Pesanan akan segera diproses.",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
        ];
    }
}
