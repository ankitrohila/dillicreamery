<?php
namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function validate(string $code, float $amount): array
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Invalid coupon code'];
        }

        if (!$coupon->isValid($amount)) {
            if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
                return ['valid' => false, 'message' => 'This coupon has expired'];
            }
            if ($coupon->min_order_amount && $amount < $coupon->min_order_amount) {
                return ['valid' => false, 'message' => 'Minimum order amount ₹' . $coupon->min_order_amount . ' required'];
            }
            return ['valid' => false, 'message' => 'Coupon is not valid'];
        }

        $discount = $coupon->calculateDiscount($amount);
        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'message' => 'Coupon applied! You save ₹' . number_format($discount, 2),
        ];
    }
}
