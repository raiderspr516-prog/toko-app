<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class OrderCompletedNotification extends Notification
{
    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pesanan Selesai',
            'message' => "Pesanan {$this->order->order_number} telah selesai. Terima kasih telah berbelanja!",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
        ];
    }
}
