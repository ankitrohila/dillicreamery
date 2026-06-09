<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionItem extends Model
{
    protected $fillable = ['subscription_id', 'product_id', 'variation_id', 'quantity', 'price'];
    protected $casts = ['price' => 'decimal:2'];

    public function subscription(): BelongsTo { return $this->belongsTo(Subscription::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variation(): BelongsTo { return $this->belongsTo(ProductVariation::class, 'variation_id'); }
}
