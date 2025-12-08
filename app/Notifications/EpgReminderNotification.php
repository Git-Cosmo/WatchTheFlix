<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EpgReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $reminderData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $reminderData)
    {
        $this->reminderData = $reminderData;
    }

    /**
     * Get the notification's delivery channels.
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
        $program = $this->reminderData['program'];
        
        return (new MailMessage)
            ->subject($this->reminderData['title'])
            ->greeting('Program Reminder')
            ->line($this->reminderData['message'])
            ->line('**' . $program['title'] . '**')
            ->line($program['description'] ?? 'No description available.')
            ->line('Channel: ' . $program['channel']['name'])
            ->line('Start Time: ' . date('M d, Y H:i', strtotime($program['start_time'])))
            ->action('Watch Now', $this->reminderData['action_url'])
            ->line('Don\'t miss it!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'epg_reminder',
            'title' => $this->reminderData['title'],
            'message' => $this->reminderData['message'],
            'program' => $this->reminderData['program'],
            'action_url' => $this->reminderData['action_url'],
        ];
    }
}
