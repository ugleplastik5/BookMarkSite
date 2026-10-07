<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use App\Notifications\NewLikeNotification;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Переключить лайк на отзыв.
     */
    public function toggleReview(Review $review)
    {
        return $this->toggle($review);
    }

    /**
     * Переключить лайк на комментарий.
     */
    public function toggleComment(Comment $comment)
    {
        return $this->toggle($comment);
    }

    /**
     * Общая логика переключения лайка.
     */
    private function toggle(Review|Comment $model)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        $existing = $model->likes()->where('user_id', $me->id)->first();

        if ($existing) {
            // Лайк уже есть — снимаем
            $existing->delete();
        } else {
            // Создаём лайк
            $model->likes()->create(['user_id' => $me->id]);

            // Определяем владельца и URL для уведомления
            $owner = null;
            $type  = null;
            $url   = null;

            if ($model instanceof Review) {
                $owner = $model->user;
                $type  = 'отзыв';
                $url   = route('reviews.show', $model);
            } elseif ($model instanceof Comment) {
                $owner = $model->user;
                $type  = 'комментарий';
                $url   = route('reviews.show', $model->review);
            }

            // Уведомляем владельца, если это не сам лайкнувший
            if ($owner && $owner->id !== $me->id) {
                $owner->notify(new NewLikeNotification($me, $type, $url));
            }
        }

        return back();
    }
}