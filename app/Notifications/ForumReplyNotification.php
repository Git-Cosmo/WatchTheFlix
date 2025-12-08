<?php

namespace App\Notifications;

use App\Models\ForumPost;
use App\Models\ForumThread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForumReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ForumThread $thread,
        public ForumPost $post
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
            ->subject('New Reply in: '.$this->thread->title)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line($this->post->user->name.' replied to a thread you\'re subscribed to.')
            ->line('Thread: '.$this->thread->title)
            ->action('View Thread', route('forum.thread', $this->thread))
            ->line('You can unsubscribe from this thread at any time.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'forum_reply',
            'message' => '💬 '.$this->post->user->name.' replied to "'.$this->thread->title.'"',
            'action_url' => route('forum.thread', $this->thread),
            'action_text' => 'View Thread',
            'thread_id' => $this->thread->id,
            'post_id' => $this->post->id,
        ];
    }
}
