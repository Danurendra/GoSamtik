<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waste_size',
        'estimated_weight',
        'subscription_id',
        'service_type_id',
        'driver_id',
        'collection_type',
        'scheduled_date',
        'scheduled_time_start',
        'scheduled_time_end',
        'status',
        'service_address',
        'latitude',
        'longitude',
        'notes',
        'special_instructions',
        'total_amount',
        'completion_photo',
        'completed_at',
        'driver_notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time_start' => 'datetime:H:i',
        'scheduled_time_end' => 'datetime:H:i',
        'completed_at' => 'datetime',
        'total_amount' => 'decimal: 2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function routeStop()
    {
        return $this->hasOne(RouteStop::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint:: class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduledFor($query, $date)
    {
        return $query->whereDate('scheduled_date', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_date', '>=', now()->toDateString())
                    ->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Status helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeRated(): bool
    {
        return $this->status === 'completed' && !$this->rating;
    }

    // Get status badge color
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'in_progress' => 'indigo',
            'completed' => 'green',
            'cancelled' => 'red',
            'missed' => 'gray',
            default => 'gray',
        };
    }

    // Get time window string
    public function getTimeWindowAttribute(): string
    {
        return $this->scheduled_time_start->format('g:i A') . ' - ' .
               $this->scheduled_time_end->format('g:i A');
    }
}
