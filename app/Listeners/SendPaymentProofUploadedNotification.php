<?php

namespace App\Listeners;

use App\Events\PaymentProofUploaded;
use App\Models\Admin;
use App\Notifications\NewPaymentProofAdminNotification;

class SendPaymentProofUploadedNotification
{
    public function handle(PaymentProofUploaded $event): void
    {
        Admin::where('status', 'active')->get()->each(
            fn (Admin $admin) => $admin->notify(new NewPaymentProofAdminNotification($event->proof))
        );
    }
}
