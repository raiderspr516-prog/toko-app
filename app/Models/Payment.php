<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'method', 'amount', 'status', 'gateway_reference', 'expired_at'];

    protected function casts(): array
    {
        return ['expired_at' => 'datetime'];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function proofs()
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function latestProof()
    {
        return $this->hasOne(PaymentProof::class)->latestOfMany();
    }
}
