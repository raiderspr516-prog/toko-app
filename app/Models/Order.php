<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    public const CANCELLABLE_STATUSES = ['pending', 'waiting_payment'];

    protected $fillable = [
        'order_number', 'user_id', 'address_id', 'status',
        'subtotal', 'shipping_cost', 'payment_fee', 'discount_amount', 'grand_total',
        'coupon_id', 'notes', 'cancelled_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function isCancellableByCustomer(): bool
    {
        return in_array($this->status, self::CANCELLABLE_STATUSES, true);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pembayaran Dipilih',
            'waiting_payment' => 'Menunggu Pembayaran',
            'payment_review' => 'Menunggu Verifikasi Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kedaluwarsa',
            default => ucfirst($this->status),
        };
    }
}
