<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Course extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = ['title', 'slug', 'description', 'thumbnail', 'instructor_id', 'price', 'sale_price', 'level', 'duration_hours', 'is_published', 'is_featured', 'meta_title', 'meta_description'];
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function instructor(): BelongsTo { return $this->belongsTo(User::class, 'instructor_id'); }
    public function modules(): HasMany { return $this->hasMany(CourseModule::class)->orderBy('sort_order'); }
    public function enrollments(): HasMany { return $this->hasMany(CourseEnrollment::class); }
    public function scopePublished($query) { return $query->where('is_published', true); }
}
