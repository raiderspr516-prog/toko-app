<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    protected $fillable = [
        'payment_id', 'order_id', 'user_id', 'file_path', 'status',
        'admin_note', 'verified_at', 'verified_by', 'uploaded_at',
    ];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime', 'uploaded_at' => 'datetime'];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }
}
