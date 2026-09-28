<?php

namespace App\Services\Payment;

class PaymentWebhookResult
{
    public function __construct(
        public readonly bool $isValid,
        public readonly ?string $gatewayReference = null,
        public readonly ?string $status = null,
        public readonly ?string $message = null,
    ) {}
}
