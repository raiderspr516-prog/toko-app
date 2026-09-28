<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Notifications\OrderCompletedNotification;

class SendOrderCompletedNotification
{
    public function handle(OrderCompleted $event): void
    {
        $event->order->user->notify(new OrderCompletedNotification($event->order));
    }
}
