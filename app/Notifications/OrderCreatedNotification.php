<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pesanan Dibuat',
            'message' => "Pesanan {$this->order->order_number} berhasil dibuat. Silakan lakukan pembayaran.",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
        ];
    }
}
