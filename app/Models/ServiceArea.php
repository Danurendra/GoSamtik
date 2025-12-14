<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'state',
        'postal_code_pattern',
        'boundary_coordinates',
        'extra_fee',
        'is_active',
    ];

    protected $casts = [
        'boundary_coordinates' => 'array',
        'is_active' => 'boolean',
        'extra_fee' => 'decimal:2',
    ];

    // Relationships
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Check if postal code is in service area
    public function coversPostalCode(string $postalCode): bool
    {
        if (empty($this->postal_code_pattern)) {
            return true;
        }

        return preg_match('/' . $this->postal_code_pattern . '/', $postalCode) === 1;
    }
}