<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'day_of_week',
        'preferred_time_start',
        'preferred_time_end',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'preferred_time_start' => 'datetime: H:i',
        'preferred_time_end' => 'datetime:H:i',
    ];

    // Relationships
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Get day number (0 = Sunday, 1 = Monday, etc.)
    public function getDayNumberAttribute(): int
    {
        $days = ['sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 
                 'thursday' => 4, 'friday' => 5, 'saturday' => 6];
        
        return $days[$this->day_of_week] ?? 0;
    }

    // Get formatted day name
    public function getDayNameAttribute(): string
    {
        return ucfirst($this->day_of_week);
    }

    // Get time window string
    public function getTimeWindowAttribute(): string
    {
        return $this->preferred_time_start->format('g:i A') . ' - ' . 
               $this->preferred_time_end->format('g:i A');
    }
}