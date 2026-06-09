<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FounderAchievement extends Model
{
    protected $fillable = ['title', 'description', 'year', 'icon', 'image', 'sort_order'];
}
