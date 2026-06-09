<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultancyBooking extends Model
{
    protected $fillable = ['lead_id', 'user_id', 'service_id', 'package_id', 'scheduled_at', 'duration_minutes', 'status', 'meeting_link', 'notes', 'amount', 'payment_status'];
    protected $casts = ['scheduled_at' => 'datetime', 'amount' => 'decimal:2'];

    public function lead(): BelongsTo { return $this->belongsTo(ConsultancyLead::class, 'lead_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function service(): BelongsTo { return $this->belongsTo(ConsultancyService::class, 'service_id'); }
    public function package(): BelongsTo { return $this->belongsTo(ConsultancyPackage::class, 'package_id'); }
}
