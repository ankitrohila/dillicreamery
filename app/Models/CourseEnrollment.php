<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnrollment extends Model
{
    protected $fillable = ['course_id', 'user_id', 'payment_id', 'amount_paid', 'completed_at', 'progress_percent', 'enrolled_at'];
    protected $casts = [
        'completed_at' => 'datetime',
        'enrolled_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
