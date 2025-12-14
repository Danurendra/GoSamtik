<?php

namespace App\Notifications;

use App\Models\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollectionReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Collection $collection
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $driverInfo = $this->collection->driver 
            ? "Your driver **{$this->collection->driver->user->name}** will arrive in a **{$this->collection->driver->vehicle_type}** ({$this->collection->driver->vehicle_plate})."
            : "A driver will be assigned to your collection soon.";

        return (new MailMessage)
            ->subject('Collection Reminder - Tomorrow!  - EcoCollect')
            ->greeting("Hello {$notifiable->name}!")
            ->line('This is a friendly reminder that you have a waste collection scheduled for tomorrow.')
            ->line("**Service:** {$this->collection->serviceType->name}")
            ->line("**Date:** {$this->collection->scheduled_date->format('l, F d, Y')}")
            ->line("**Time:** {$this->collection->scheduled_time_start} - {$this->collection->scheduled_time_end}")
            ->line($driverInfo)
            ->line('**Please ensure your waste is ready for collection before the scheduled time.**')
            ->action('View Collection Details', route('collections.show', $this->collection))
            ->line('Thank you for choosing EcoCollect!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'collection_reminder',
            'collection_id' => $this->collection->id,
            'service_type' => $this->collection->serviceType->name,
            'scheduled_date' => $this->collection->scheduled_date->toDateString(),
            'message' => "Reminder: Your {$this->collection->serviceType->name} collection is scheduled for tomorrow.",
        ];
    }
}