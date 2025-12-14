<?php

namespace App\Notifications;

use App\Models\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollectionConfirmedNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Collection Confirmed - EcoCollect')
            ->greeting("Hello {$notifiable->name}!")
            ->line('Your waste collection has been confirmed.')
            ->line("**Service:** {$this->collection->serviceType->name}")
            ->line("**Date:** {$this->collection->scheduled_date->format('l, F d, Y')}")
            ->line("**Time:** {$this->collection->scheduled_time_start} - {$this->collection->scheduled_time_end}")
            ->line("**Address:** {$this->collection->service_address}")
            ->action('View Collection Details', route('collections.show', $this->collection))
            ->line('Thank you for choosing EcoCollect!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'collection_confirmed',
            'collection_id' => $this->collection->id,
            'service_type' => $this->collection->serviceType->name,
            'scheduled_date' => $this->collection->scheduled_date->toDateString(),
            'message' => "Your {$this->collection->serviceType->name} collection has been confirmed for {$this->collection->scheduled_date->format('M d, Y')}.",
        ];
    }
}