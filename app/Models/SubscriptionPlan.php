<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SubscriptionPlan extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'description', 'frequency', 'price_per_delivery', 'min_deliveries', 'is_active', 'features'];
    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
        'price_per_delivery' => 'decimal:2',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function subscriptions(): HasMany { return $this->hasMany(Subscription::class, 'plan_id'); }
}
