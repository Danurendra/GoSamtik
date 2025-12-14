<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'service_type_id',
        'frequency_per_week',
        'monthly_price',
        'per_pickup_price',
        'discount_percentage',
        'description',
        'features',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'monthly_price' => 'decimal:2',
        'per_pickup_price' => 'decimal:2',
        'discount_percentage' => 'decimal: 2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // Relationships
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Calculate estimated monthly pickups
    public function getMonthlyPickupsAttribute(): int
    {
        return $this->frequency_per_week * 4; // Approximate
    }

    // Get frequency label
    public function getFrequencyLabelAttribute(): string
    {
        return match($this->frequency_per_week) {
            1 => 'Once a week',
            2 => 'Twice a week',
            3 => '3 times a week',
            7 => 'Daily',
            default => $this->frequency_per_week .  ' times a week',
        };
    }
}