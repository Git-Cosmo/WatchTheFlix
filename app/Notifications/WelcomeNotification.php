<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to WatchTheFlix!')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Welcome to WatchTheFlix! We\'re excited to have you join our community.')
            ->line('Start exploring thousands of movies and TV shows, create watchlists, join forum discussions, and much more.')
            ->action('Browse Content', route('media.index'))
            ->line('Thank you for joining WatchTheFlix!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'welcome',
            'message' => '🎉 Welcome to WatchTheFlix! Start exploring thousands of movies and TV shows.',
            'action_url' => route('media.index'),
            'action_text' => 'Browse Content',
        ];
    }
}
