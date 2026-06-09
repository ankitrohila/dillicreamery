<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['title', 'description', 'youtube_url', 'thumbnail', 'category', 'is_featured', 'sort_order'];
    protected $casts = ['is_featured' => 'boolean'];

    public function scopeFeatured($query) { return $query->where('is_featured', true); }

    public function getYoutubeIdAttribute(): string
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_url, $matches);
        return $matches[1] ?? '';
    }
}
