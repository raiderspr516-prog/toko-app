<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'minimum_purchase', 'maximum_discount',
        'start_date', 'end_date', 'usage_limit', 'used_count', 'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isCurrentlyValid(): bool
    {
        $today = now()->startOfDay();

        return $this->status === 'active'
            && $today->betweenIncluded($this->start_date, $this->end_date)
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
