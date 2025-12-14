<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $serviceTypes = [
            [
                'name' => 'General Waste',
                'slug' => 'general-waste',
                'description' => 'Regular household waste and non-recyclable materials.  Perfect for everyday trash disposal.',
                'base_price' => 15.00,
                'icon' => 'trash',
                'color' => '#6b7280',
                'requirements' => [
                    'Use approved garbage bags',
                    'Maximum 50kg per pickup',
                    'No hazardous materials',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Recyclables',
                'slug' => 'recyclables',
                'description' => 'Paper, cardboard, plastic, glass, and metal items. Help save the environment through recycling.',
                'base_price' => 12.00,
                'icon' => 'recycle',
                'color' => '#22c55e',
                'requirements' => [
                    'Clean and dry materials only',
                    'Separate by material type',
                    'Flatten cardboard boxes',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Organic Waste',
                'slug' => 'organic-waste',
                'description' => 'Food scraps, yard waste, and compostable materials. Turn your waste into nutrient-rich compost.',
                'base_price' => 10.00,
                'icon' => 'leaf',
                'color' => '#84cc16',
                'requirements' => [
                    'Use compostable bags',
                    'No plastic or metal',
                    'No meat or dairy (optional)',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Hazardous Waste',
                'slug' => 'hazardous-waste',
                'description' => 'Batteries, paint, chemicals, and electronics. Safe disposal of dangerous materials.',
                'base_price' => 35.00,
                'icon' => 'warning',
                'color' => '#ef4444',
                'requirements' => [
                    'Original containers when possible',
                    'Label all materials',
                    'Schedule in advance',
                    'Present during pickup',
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Bulk Items',
                'slug' => 'bulk-items',
                'description' => 'Furniture, appliances, mattresses, and large items. We handle the heavy lifting for you.',
                'base_price' => 50.00,
                'icon' => 'box',
                'color' => '#8b5cf6',
                'requirements' => [
                    'Place items at curb',
                    'Schedule 48 hours in advance',
                    'Maximum 3 large items per pickup',
                    'Remove doors from appliances',
                ],
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($serviceTypes as $type) {
            ServiceType::create($type);
        }
    }
}