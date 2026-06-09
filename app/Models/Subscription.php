<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = ['subscription_number', 'user_id', 'plan_id', 'address_id', 'status', 'start_date', 'end_date', 'next_delivery_date', 'total_deliveries', 'completed_deliveries', 'pause_from', 'pause_until', 'cancellation_reason', 'cancelled_at'];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_delivery_date' => 'date',
        'pause_from' => 'date',
        'pause_until' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function plan(): BelongsTo { return $this->belongsTo(SubscriptionPlan::class, 'plan_id'); }
    public function address(): BelongsTo { return $this->belongsTo(Address::class); }
    public function items(): HasMany { return $this->hasMany(SubscriptionItem::class); }
    public function deliveries(): HasMany { return $this->hasMany(SubscriptionDelivery::class); }
    public function payments(): HasMany { return $this->hasMany(SubscriptionPayment::class); }

    public function scopeActive($query) { return $query->where('status', 'active'); }
    public function scopePaused($query) { return $query->where('status', 'paused'); }

    public function pause(\Carbon\Carbon $from, \Carbon\Carbon $until): void
    {
        $this->update(['status' => 'paused', 'pause_from' => $from, 'pause_until' => $until]);
    }

    public function resume(): void
    {
        $this->update(['status' => 'active', 'pause_from' => null, 'pause_until' => null]);
    }

    public function cancel(string $reason = ''): void
    {
        $this->update(['status' => 'cancelled', 'cancellation_reason' => $reason, 'cancelled_at' => now()]);
    }
}
