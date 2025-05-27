<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KomentarBaruNotification extends Notification
{
    use Queueable;

    protected $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'komentar_id' => $this->comment->id,
            'nama' => $this->comment->user->name,
            'pesan' => $this->comment->pesan,
        ];
    }
}
