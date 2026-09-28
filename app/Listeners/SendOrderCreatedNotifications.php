<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Admin;
use App\Notifications\NewOrderAdminNotification;
use App\Notifications\OrderCreatedNotification;

class SendOrderCreatedNotifications
{
    public function handle(OrderCreated $event): void
    {
        $event->order->user->notify(new OrderCreatedNotification($event->order));

        Admin::where('status', 'active')->get()->each(
            fn (Admin $admin) => $admin->notify(new NewOrderAdminNotification($event->order))
        );
    }
}
