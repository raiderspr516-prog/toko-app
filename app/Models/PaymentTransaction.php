<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = ['payment_id', 'gateway', 'request_payload', 'response_payload', 'status'];

    protected function casts(): array
    {
        return ['request_payload' => 'array', 'response_payload' => 'array'];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
