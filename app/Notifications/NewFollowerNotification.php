<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification
{
    use Queueable;

    public function __construct(public User $follower)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'new_follower',
            'user_id'   => $this->follower->id,
            'user_name' => $this->follower->name,
            'message'   => $this->follower->name . ' подписался на вас',
            'url'       => route('user.profile', $this->follower),
        ];
    }
}