<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;

class PostRejectedNotification extends Notification
{
    use Queueable;

    public $post;
    public $a;
    public $reason;

    public function __construct($post, $a, $reason)
    {
        $this->post = $post;
        $this->a = $a;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database']; // Save notification in DB
    }

    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            // 'reason' => $this->post->is_approved == 3 ? 'Your post violates our guidelines.' : null
            'reason' => $this->reason,
        ];
    }
}
