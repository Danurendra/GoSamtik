<?php

namespace App\Notifications;

use App\Models\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollectionCompletedNotification extends Notification implements ShouldQueue
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
            ->subject('Collection Completed - EcoCollect')
            ->greeting("Hello {$notifiable->name}!")
            ->line('Great news! Your waste collection has been completed successfully.')
            ->line("**Service:** {$this->collection->serviceType->name}")
            ->line("**Completed at:** {$this->collection->completed_at->format('F d, Y g:i A')}")
            ->line('We hope you are satisfied with our service.  Please take a moment to rate your experience.')
            ->action('Rate This Collection', route('collections.show', $this->collection))
            ->line('Thank you for helping make our environment cleaner!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'collection_completed',
            'collection_id' => $this->collection->id,
            'service_type' => $this->collection->serviceType->name,
            'completed_at' => $this->collection->completed_at->toDateTimeString(),
            'message' => "Your {$this->collection->serviceType->name} collection has been completed.",
        ];
    }
}