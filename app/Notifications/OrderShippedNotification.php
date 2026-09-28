<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class OrderShippedNotification extends Notification
{
    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pesanan Dikirim',
            'message' => "Pesanan {$this->order->order_number} sedang dalam pengiriman.",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
        ];
    }
}
