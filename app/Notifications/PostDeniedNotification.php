<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PostDeniedNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $reason;

    public function __construct($post, $reason)
    {
        $this->post = $post;
        $this->reason = $reason;
    }

    // Store notification in database
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Your post titled '{$this->post->title}' has been denied by the admin.",
            'post_id' => $this->post->id,
            'status' => 'denied',
            'reason' => $this->reason ?? 'No reason provided',
        ];
    }

    // If you want to send via email (optional)
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Post Denied')
            ->line("Your post titled '{$this->post->title}' has been denied by the admin.")
            ->line("Reason: " . ($this->reason ?? 'No reason provided'))
            ->action('View Post', url('/posts/' . $this->post->id));
    }

    public function via($notifiable)
    {
        return ['database']; // Store notification in database
    }
}
