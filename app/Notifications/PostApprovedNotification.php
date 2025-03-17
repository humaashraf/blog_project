<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PostApprovedNotification extends Notification
{
    use Queueable;

    public $post; // Public variable banayein

    public function __construct($post)
    {
        $this->post = $post; // Ensure karein ke post object mil raha hai
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Notification ka channel define karein
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->post->id, // Ensure karein ke post ka ID exist karta hai
            'title' => $this->post->title,
            'message' => 'Your post has been approved successfully!',
        ];
    }
}
