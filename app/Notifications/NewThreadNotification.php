<?php

namespace App\Notifications;

use App\Models\ForumThread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewThreadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ForumThread $thread
    ) {
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
            ->subject('New Thread in '.$this->thread->category->name)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('A new thread has been created in '.$this->thread->category->name.'.')
            ->line('Thread: '.$this->thread->title)
            ->line('By: '.$this->thread->user->name)
            ->action('View Thread', route('forum.thread', $this->thread))
            ->line('Join the discussion now!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_thread',
            'message' => '📝 New thread in '.$this->thread->category->name.': "'.$this->thread->title.'"',
            'action_url' => route('forum.thread', $this->thread),
            'action_text' => 'View Thread',
            'thread_id' => $this->thread->id,
        ];
    }
}
