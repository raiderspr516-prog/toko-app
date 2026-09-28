<?php

namespace App\Listeners;

use App\Events\PaymentApproved;
use App\Notifications\PaymentApprovedNotification;

class SendPaymentApprovedNotification
{
    public function handle(PaymentApproved $event): void
    {
        $event->order->user->notify(new PaymentApprovedNotification($event->order));
    }
}
