<?php

namespace App\Providers;

use App\Events\OrderCompleted;
use App\Events\OrderCreated;
use App\Events\OrderShipped;
use App\Events\PaymentApproved;
use App\Events\PaymentProofUploaded;
use App\Events\PaymentRejected;
use App\Listeners\SendOrderCompletedNotification;
use App\Listeners\SendOrderCreatedNotifications;
use App\Listeners\SendOrderShippedNotification;
use App\Listeners\SendPaymentApprovedNotification;
use App\Listeners\SendPaymentProofUploadedNotification;
use App\Listeners\SendPaymentRejectedNotification;
use App\Services\Payment\MidtransPaymentService;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\QRISManualPaymentService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Titik tunggal untuk mengganti provider pembayaran aktif.
        // Ganti nilai config('payment.provider') / .env PAYMENT_PROVIDER
        // untuk pindah provider tanpa menyentuh kode checkout/order sama sekali.
        $this->app->bind(PaymentGatewayInterface::class, function () {
            return match (config('payment.provider', 'qris_manual')) {
                'midtrans' => $this->app->make(MidtransPaymentService::class),
                default => $this->app->make(QRISManualPaymentService::class),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(OrderCreated::class, SendOrderCreatedNotifications::class);
        Event::listen(PaymentApproved::class, SendPaymentApprovedNotification::class);
        Event::listen(PaymentRejected::class, SendPaymentRejectedNotification::class);
        Event::listen(OrderShipped::class, SendOrderShippedNotification::class);
        Event::listen(OrderCompleted::class, SendOrderCompletedNotification::class);
        Event::listen(PaymentProofUploaded::class, SendPaymentProofUploadedNotification::class);
    }
}
