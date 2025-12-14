<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServiceTypeSeeder::class,
            SubscriptionPlanSeeder::class,
            ServiceAreaSeeder::class,
            HolidaySeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            DemoCollectionSeeder::class,
        ]);

        $this->command->info('Database seeded successfully! ');
        $this->command->newLine();
        $this->command->info('Demo Accounts: ');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@ecocollect.com', 'password'],
                ['Provider', 'provider@ecocollect.com', 'password'],
                ['Customer', 'customer@example.com', 'password'],
                ['Driver', 'mike@ecocollect.com', 'password'],
            ]
        );
    }
}