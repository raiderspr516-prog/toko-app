<?php

namespace App\Services\Payment;

/**
 * Value object hasil generatePayment() — seragam untuk provider manapun.
 */
class PaymentResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $qrisImageUrl = null,
        public readonly ?string $gatewayReference = null,
        public readonly ?string $message = null,
    ) {}
}
