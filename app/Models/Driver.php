<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_expiry',
        'vehicle_type',
        'vehicle_plate',
        'vehicle_capacity',
        'availability_status',
        'current_latitude',
        'current_longitude',
        'location_updated_at',
        'average_rating',
        'total_collections',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'location_updated_at' => 'datetime',
        'current_latitude' => 'decimal: 8',
        'current_longitude' => 'decimal:8',
        'average_rating' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available');
    }

    public function scopeOnRoute($query)
    {
        return $query->where('availability_status', 'on_route');
    }

    // Helpers
    public function isAvailable(): bool
    {
        return $this->availability_status === 'available';
    }

    public function updateLocation(float $latitude, float $longitude): void
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'location_updated_at' => now(),
        ]);
    }

    // Update average rating
    public function recalculateRating(): void
    {
        $average = $this->ratings()->avg('overall_rating') ?? 0;
        $this->update(['average_rating' => round($average, 2)]);
    }
}