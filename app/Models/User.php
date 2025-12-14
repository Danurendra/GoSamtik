<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_photo',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment:: class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function notifications()
    {
        return $this->hasMany(CustomNotification::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    public function activeSubscriptions()
    {
        return $this->subscriptions()->where('status', 'active');
    }
}