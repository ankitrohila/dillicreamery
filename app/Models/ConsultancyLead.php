<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultancyLead extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'business_name', 'message', 'service_id', 'status', 'source', 'assigned_to', 'notes'];

    public function service(): BelongsTo { return $this->belongsTo(ConsultancyService::class, 'service_id'); }
    public function assignedTo(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function bookings(): HasMany { return $this->hasMany(ConsultancyBooking::class, 'lead_id'); }
    public function scopeByStatus($query, string $status) { return $query->where('status', $status); }
}
