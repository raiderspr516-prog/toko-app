<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentWebhook extends Model
{
    protected $fillable = ['gateway', 'event_type', 'raw_payload', 'signature', 'is_verified', 'processed_at'];

    protected function casts(): array
    {
        return ['raw_payload' => 'array', 'is_verified' => 'boolean', 'processed_at' => 'datetime'];
    }
}
