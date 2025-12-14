<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate routes for tomorrow at 8 PM every day
        $schedule->command('routes:generate')
            ->dailyAt('20:00')
            ->withoutOverlapping();

        // Send collection reminders at 6 PM every day
        $schedule->command('collections:send-reminders')
            ->dailyAt('18:00')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console. php');
    }
}