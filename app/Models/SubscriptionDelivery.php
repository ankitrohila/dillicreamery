<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionDelivery extends Model
{
    protected $fillable = ['subscription_id', 'scheduled_date', 'status', 'delivery_agent_id', 'notes', 'delivered_at'];
    protected $casts = ['scheduled_date' => 'date', 'delivered_at' => 'datetime'];

    public function subscription(): BelongsTo { return $this->belongsTo(Subscription::class); }
}
