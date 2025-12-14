<?php

namespace Database\Seeders;

use App\Models\ServiceArea;
use Illuminate\Database\Seeder;

class ServiceAreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Downtown',
                'city' => 'Metro City',
                'state' => 'State',
                'postal_code_pattern' => '100[0-9]{2}',
                'extra_fee' => 0.00,
                'is_active' => true,
            ],
            [
                'name' => 'North District',
                'city' => 'Metro City',
                'state' => 'State',
                'postal_code_pattern' => '101[0-9]{2}',
                'extra_fee' => 0.00,
                'is_active' => true,
            ],
            [
                'name' => 'South District',
                'city' => 'Metro City',
                'state' => 'State',
                'postal_code_pattern' => '102[0-9]{2}',
                'extra_fee' => 2.00,
                'is_active' => true,
            ],
            [
                'name' => 'East Suburbs',
                'city' => 'Metro City',
                'state' => 'State',
                'postal_code_pattern' => '103[0-9]{2}',
                'extra_fee' => 5.00,
                'is_active' => true,
            ],
            [
                'name' => 'West Suburbs',
                'city' => 'Metro City',
                'state' => 'State',
                'postal_code_pattern' => '104[0-9]{2}',
                'extra_fee' => 5.00,
                'is_active' => true,
            ],
        ];

        foreach ($areas as $area) {
            ServiceArea::create($area);
        }
    }
}