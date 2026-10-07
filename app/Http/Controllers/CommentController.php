<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Добавить комментарий к отзыву.
     */
    public function store(Request $request, Review $review)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        if (! $this->canViewReview($me, $review)) {
            abort(403, 'У вас нет доступа к этому отзыву');
        }

        $validated = $request->validate([
            'body' => 'required|string|min:1|max:2000',
        ]);

        $comment = $me->comments()->create([
            'review_id' => $review->id,
            'body'      => $validated['body'],
        ]);

        // Уведомляем автора отзыва (если это не сам комментатор)
        if ($review->user_id !== $me->id) {
            $review->user->notify(new NewCommentNotification($comment));
        }

        return redirect()
            ->route('reviews.show', $review)
            ->with('success', 'Комментарий добавлен');
    }

    /**
     * Удалить комментарий.
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Это не ваш комментарий');
        }

        $comment->delete();

        return back()->with('success', 'Комментарий удалён');
    }

    /**
     * Проверка доступа к отзыву.
     */
    private function canViewReview(?User $me, Review $review): bool
    {
        if ($me && $review->user_id === $me->id) {
            return true;
        }

        if ($review->visibility === Review::VISIBILITY_PUBLIC) {
            return true;
        }

        if ($review->visibility === Review::VISIBILITY_FRIENDS) {
            return $me && $me->isFriendsWith($review->user);
        }

        return false;
    }
}