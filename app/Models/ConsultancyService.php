<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ConsultancyService extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'price', 'duration_minutes', 'is_active', 'sort_order'];
    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function leads(): HasMany { return $this->hasMany(ConsultancyLead::class, 'service_id'); }
    public function bookings(): HasMany { return $this->hasMany(ConsultancyBooking::class, 'service_id'); }
}
