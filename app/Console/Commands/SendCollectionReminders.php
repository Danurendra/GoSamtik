<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Notifications\CollectionReminderNotification;
use Illuminate\Console\Command;

class SendCollectionReminders extends Command
{
    protected $signature = 'collections:send-reminders';
    protected $description = 'Send reminder notifications for collections scheduled tomorrow';

    public function handle(): int
    {
        $collections = Collection::whereDate('scheduled_date', now()->addDay())
            ->whereIn('status', ['confirmed', 'pending'])
            ->with(['user', 'serviceType', 'driver. user'])
            ->get();

        $count = 0;

        foreach ($collections as $collection) {
            $collection->user->notify(new CollectionReminderNotification($collection));
            $count++;
        }

        $this->info("Sent {$count} reminder notifications.");

        return Command::SUCCESS;
    }
}