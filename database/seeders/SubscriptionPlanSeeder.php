<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $serviceTypes = ServiceType::all()->keyBy('slug');

        // General Waste Plans
        $this->createPlansForService($serviceTypes['general-waste'], [
            [
                'name' => 'Basic Weekly',
                'slug' => 'general-basic-weekly',
                'frequency_per_week' => 1,
                'monthly_price' => 49.99,
                'per_pickup_price' => 12.50,
                'discount_percentage' => 17,
                'description' => 'Perfect for small households',
                'features' => [
                    'Once weekly pickup',
                    'Up to 2 bags per pickup',
                    'Email notifications',
                    'Flexible scheduling',
                ],
                'is_popular' => false,
            ],
            [
                'name' => 'Standard Twice Weekly',
                'slug' => 'general-standard',
                'frequency_per_week' => 2,
                'monthly_price' => 89.99,
                'per_pickup_price' => 11.25,
                'discount_percentage' => 25,
                'description' => 'Most popular for families',
                'features' => [
                    'Twice weekly pickup',
                    'Up to 3 bags per pickup',
                    'Email & SMS notifications',
                    'Priority scheduling',
                    'Free rescheduling',
                ],
                'is_popular' => true,
            ],
            [
                'name' => 'Premium Daily',
                'slug' => 'general-premium',
                'frequency_per_week' => 5,
                'monthly_price' => 179.99,
                'per_pickup_price' => 9.00,
                'discount_percentage' => 40,
                'description' => 'For businesses and large households',
                'features' => [
                    'Monday to Friday pickup',
                    'Unlimited bags',
                    'Priority support',
                    'Dedicated driver',
                    'Real-time tracking',
                ],
                'is_popular' => false,
            ],
        ]);

        // Recyclables Plans
        $this->createPlansForService($serviceTypes['recyclables'], [
            [
                'name' => 'Eco Weekly',
                'slug' => 'recycle-weekly',
                'frequency_per_week' => 1,
                'monthly_price' => 39.99,
                'per_pickup_price' => 10.00,
                'discount_percentage' => 17,
                'description' => 'Start your recycling journey',
                'features' => [
                    'Once weekly pickup',
                    'Free recycling bins',
                    'Sorting guide included',
                ],
                'is_popular' => false,
            ],
            [
                'name' => 'Green Bi-Weekly',
                'slug' => 'recycle-biweekly',
                'frequency_per_week' => 2,
                'monthly_price' => 69.99,
                'per_pickup_price' => 8.75,
                'discount_percentage' => 27,
                'description' => 'Best for eco-conscious families',
                'features' => [
                    'Twice weekly pickup',
                    'Multiple recycling bins',
                    'Monthly impact report',
                    'Priority scheduling',
                ],
                'is_popular' => true,
            ],
        ]);

        // Organic Waste Plans
        $this->createPlansForService($serviceTypes['organic-waste'], [
            [
                'name' => 'Compost Weekly',
                'slug' => 'organic-weekly',
                'frequency_per_week' => 1,
                'monthly_price' => 34.99,
                'per_pickup_price' => 8.75,
                'discount_percentage' => 12,
                'description' => 'Weekly composting service',
                'features' => [
                    'Once weekly pickup',
                    'Free compost bin',
                    'Compostable bags included',
                ],
                'is_popular' => false,
            ],
            [
                'name' => 'Garden Pro',
                'slug' => 'organic-pro',
                'frequency_per_week' => 2,
                'monthly_price' => 59.99,
                'per_pickup_price' => 7.50,
                'discount_percentage' => 25,
                'description' => 'For gardens and kitchens',
                'features' => [
                    'Twice weekly pickup',
                    'Premium compost bin',
                    'Free compost return',
                    'Gardening tips newsletter',
                ],
                'is_popular' => true,
            ],
            [
                'name' => 'Farm Fresh',
                'slug' => 'organic-farm',
                'frequency_per_week' => 3,
                'monthly_price' => 79.99,
                'per_pickup_price' => 6.67,
                'discount_percentage' => 33,
                'description' => 'High-volume organic waste',
                'features' => [
                    'Three times weekly pickup',
                    'Large capacity bins',
                    'Free compost delivery',
                    'Priority scheduling',
                ],
                'is_popular' => false,
            ],
        ]);

        // Bulk Items - No subscription, one-time only
        // Hazardous Waste - No subscription, one-time only
    }

    private function createPlansForService(ServiceType $serviceType, array $plans): void
    {
        foreach ($plans as $plan) {
            SubscriptionPlan:: create(array_merge($plan, [
                'service_type_id' => $serviceType->id,
                'is_active' => true,
            ]));
        }
    }
}