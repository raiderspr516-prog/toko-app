<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function findValid(string $code, int $subtotal): Coupon
    {
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            throw new \RuntimeException('Kode kupon tidak ditemukan.');
        }

        if (! $coupon->isCurrentlyValid()) {
            throw new \RuntimeException('Kupon ini sudah tidak berlaku atau kuota habis.');
        }

        if ($subtotal < $coupon->minimum_purchase) {
            throw new \RuntimeException('Minimal belanja Rp'.number_format($coupon->minimum_purchase, 0, ',', '.').' untuk memakai kupon ini.');
        }

        return $coupon;
    }

    public function calculateDiscount(Coupon $coupon, int $subtotal): int
    {
        $discount = $coupon->type === 'percentage'
            ? (int) round($subtotal * $coupon->value / 100)
            : $coupon->value;

        if ($coupon->maximum_discount) {
            $discount = min($discount, $coupon->maximum_discount);
        }

        return min($discount, $subtotal);
    }
}
