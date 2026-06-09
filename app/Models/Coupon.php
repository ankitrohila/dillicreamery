<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_order_amount', 'max_discount_amount', 'usage_limit', 'used_count', 'starts_at', 'expires_at', 'is_active', 'applicable_to'];
    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    public function isValid(float $amount): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($this->min_order_amount && $amount < $this->min_order_amount) return false;
        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        $discount = match($this->type) {
            'percent' => ($amount * $this->value) / 100,
            'fixed' => $this->value,
            'free_shipping' => 0,
            default => 0,
        };
        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }
        return min($discount, $amount);
    }
}
