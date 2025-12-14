<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'total_stops',
        'completed_stops',
        'total_distance_km',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_distance_km' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function stops(): HasMany
    {
        return $this->hasMany(RouteStop::class)->orderBy('sequence_order');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getProgressPercentageAttribute(): int
    {
        if ($this->total_stops === 0) {
            return 0;
        }

        return (int) round(($this->completed_stops / $this->total_stops) * 100);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planned' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'end_time' => now()->format('H:i: s'),
        ]);
    }

    public function updateCompletedStops(): void
    {
        $this->update([
            'completed_stops' => $this->stops()->where('status', 'completed')->count(),
        ]);
    }
}