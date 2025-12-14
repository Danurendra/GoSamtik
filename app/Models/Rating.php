<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'user_id',
        'driver_id',
        'overall_rating',
        'timeliness_rating',
        'professionalism_rating',
        'cleanliness_rating',
        'comment',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Relationships
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // After saving, update driver's average rating
    protected static function booted()
    {
        static::saved(function ($rating) {
            if ($rating->driver) {
                $rating->driver->recalculateRating();
            }
        });
    }
}