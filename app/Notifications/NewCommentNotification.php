<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'new_comment',
            'user_id'   => $this->comment->user->id,
            'user_name' => $this->comment->user->name,
            'message'   => $this->comment->user->name . ' оставил комментарий к вашему отзыву',
            'url'       => route('reviews.show', $this->comment->review),
        ];
    }
}