<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Blog extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'thumbnail', 'author_id', 'category_id', 'tags', 'status', 'published_at', 'meta_title', 'meta_description'];
    protected $casts = ['tags' => 'array', 'published_at' => 'datetime'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function category(): BelongsTo { return $this->belongsTo(BlogCategory::class, 'category_id'); }
    public function scopePublished($query) { return $query->where('status', 'published')->where('published_at', '<=', now()); }
}
