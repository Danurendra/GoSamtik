<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'name',
        'description',
        'reschedule_to_next_day',
        'is_recurring',
    ];

    protected $casts = [
        'date' => 'date',
        'reschedule_to_next_day' => 'boolean',
        'is_recurring' => 'boolean',
    ];

    // Check if a date is a holiday
    public static function isHoliday($date): bool
    {
        return self::whereDate('date', $date)->exists();
    }

    // Get next available date (not a holiday)
    public static function getNextAvailableDate($date): \Carbon\Carbon
    {
        $carbon = \Carbon\Carbon::parse($date);
        
        while (self::isHoliday($carbon)) {
            $carbon->addDay();
        }
        
        return $carbon;
    }
}