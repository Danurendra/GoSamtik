<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;

        $holidays = [
            ['date' => "{$year}-01-01", 'name' => 'New Year\'s Day', 'is_recurring' => true],
            ['date' => "{$year}-07-04", 'name' => 'Independence Day', 'is_recurring' => true],
            ['date' => "{$year}-11-28", 'name' => 'Thanksgiving', 'is_recurring' => false],
            ['date' => "{$year}-12-25", 'name' => 'Christmas Day', 'is_recurring' => true],
            ['date' => "{$year}-12-26", 'name' => 'Day After Christmas', 'is_recurring' => true],
        ];

        // Add next year holidays too
        $nextYear = $year + 1;
        $holidays[] = ['date' => "{$nextYear}-01-01", 'name' => 'New Year\'s Day', 'is_recurring' => true];

        foreach ($holidays as $holiday) {
            Holiday::create([
                'date' => $holiday['date'],
                'name' => $holiday['name'],
                'reschedule_to_next_day' => true,
                'is_recurring' => $holiday['is_recurring'],
            ]);
        }
    }
}