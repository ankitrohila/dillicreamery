<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'designation', 'company', 'avatar', 'content', 'rating', 'type', 'reference_id', 'is_featured', 'is_approved', 'sort_order'];
    protected $casts = ['is_featured' => 'boolean', 'is_approved' => 'boolean'];

    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeApproved($query) { return $query->where('is_approved', true); }
}
