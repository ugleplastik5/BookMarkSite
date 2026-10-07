<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLikeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $liker,
        public string $likeableType,
        public string $url,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'new_like',
            'user_id'   => $this->liker->id,
            'user_name' => $this->liker->name,
            'message'   => $this->liker->name . ' лайкнул ваш ' . $this->likeableType,
            'url'       => $this->url,
        ];
    }
}