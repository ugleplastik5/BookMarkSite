<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFriendNotification extends Notification
{
    use Queueable;

    public function __construct(public User $friend)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'new_friend',
            'user_id'   => $this->friend->id,
            'user_name' => $this->friend->name,
            'message'   => $this->friend->name . ' теперь ваш друг',
            'url'       => route('user.profile', $this->friend),
        ];
    }
}