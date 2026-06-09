<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    protected $fillable = ['module_id', 'title', 'content', 'video_url', 'duration_minutes', 'type', 'is_free_preview', 'sort_order'];
    protected $casts = ['is_free_preview' => 'boolean'];

    public function module(): BelongsTo { return $this->belongsTo(CourseModule::class, 'module_id'); }
}
