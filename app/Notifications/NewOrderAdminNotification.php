<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class NewOrderAdminNotification extends Notification
{
    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Order Baru',
            'message' => "Order baru {$this->order->order_number} dari {$this->order->user->name}.",
            'order_id' => $this->order->id,
        ];
    }
}
