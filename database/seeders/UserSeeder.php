<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ecocollect.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => '123 Admin Street, City, State 12345',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Service Provider
        User::create([
            'name' => 'Service Provider',
            'email' => 'provider@ecocollect. com',
            'password' => Hash::make('password'),
            'role' => 'provider',
            'phone' => '+1234567891',
            'address' => '456 Provider Ave, City, State 12345',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Demo Customer
        User::create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1234567892',
            'address' => '789 Residential Lane, City, State 12345',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Additional Customers
        $customers = [
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'address' => '101 Oak Street, City, State 12345'],
            ['name' => 'Bob Wilson', 'email' => 'bob@example.com', 'address' => '202 Pine Avenue, City, State 12345'],
            ['name' => 'Alice Brown', 'email' => 'alice@example.com', 'address' => '303 Maple Drive, City, State 12345'],
            ['name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'address' => '404 Elm Court, City, State 12345'],
        ];

        foreach ($customers as $index => $customer) {
            User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+123456789' . ($index + 3),
                'address' => $customer['address'],
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        // Create Drivers
        $drivers = [
            [
                'user' => ['name' => 'Mike Driver', 'email' => 'mike@ecocollect.com'],
                'driver' => [
                    'license_number' => 'DL-123456',
                    'license_expiry' => now()->addYears(2),
                    'vehicle_type' => 'Truck',
                    'vehicle_plate' => 'ECO-001',
                    'vehicle_capacity' => '500kg',
                    'availability_status' => 'available',
                    'average_rating' => 4.8,
                    'total_collections' => 156,
                ],
            ],
            [
                'user' => ['name' => 'Sarah Driver', 'email' => 'sarah@ecocollect.com'],
                'driver' => [
                    'license_number' => 'DL-234567',
                    'license_expiry' => now()->addYears(3),
                    'vehicle_type' => 'Van',
                    'vehicle_plate' => 'ECO-002',
                    'vehicle_capacity' => '300kg',
                    'availability_status' => 'available',
                    'average_rating' => 4.9,
                    'total_collections' => 203,
                ],
            ],
            [
                'user' => ['name' => 'Tom Driver', 'email' => 'tom@ecocollect. com'],
                'driver' => [
                    'license_number' => 'DL-345678',
                    'license_expiry' => now()->addYears(1),
                    'vehicle_type' => 'Truck',
                    'vehicle_plate' => 'ECO-003',
                    'vehicle_capacity' => '750kg',
                    'availability_status' => 'available',
                    'average_rating' => 4.7,
                    'total_collections' => 89,
                ],
            ],
        ];

        foreach ($drivers as $driverData) {
            $user = User::create([
                'name' => $driverData['user']['name'],
                'email' => $driverData['user']['email'],
                'password' => Hash::make('password'),
                'role' => 'driver',
                'phone' => '+1234567' . rand(100, 999),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            Driver::create(array_merge($driverData['driver'], [
                'user_id' => $user->id,
            ]));
        }
    }
}