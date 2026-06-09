<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CaseStudy extends Model
{
    use HasSlug;

    protected $fillable = ['title', 'slug', 'client_name', 'industry', 'challenge', 'solution', 'result', 'thumbnail', 'images', 'is_published', 'sort_order'];
    protected $casts = ['images' => 'array', 'is_published' => 'boolean'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }
}
