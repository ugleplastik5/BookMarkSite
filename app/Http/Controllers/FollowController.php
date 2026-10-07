<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use App\Notifications\NewFollowerNotification;
use App\Notifications\NewFriendNotification;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Подписаться на пользователя.
     */
    public function store(User $user)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        if ($me->id === $user->id) {
            return back()->with('error', 'Нельзя подписаться на себя');
        }

        // Создаём подписку (если её ещё нет)
        $follow = Follow::firstOrCreate([
            'follower_id'  => $me->id,
            'following_id' => $user->id,
        ]);

        // Уведомляем только при первой подписке
        if ($follow->wasRecentlyCreated) {
            // Уведомление о новой подписке
            $user->notify(new NewFollowerNotification($me));

            // Если теперь взаимная подписка — это дружба
            if ($user->isFollowing($me)) {
                $user->notify(new NewFriendNotification($me));
                $me->notify(new NewFriendNotification($user));
            }
        }

        return back()->with('success', 'Вы подписались на ' . $user->name);
    }

    /**
     * Отписаться от пользователя.
     */
    public function destroy(User $user)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        Follow::where('follower_id', $me->id)
            ->where('following_id', $user->id)
            ->delete();

        return back()->with('success', 'Вы отписались от ' . $user->name);
    }

    /**
     * Удалить из друзей (удаляем обе записи).
     */
    public function unfriend(User $user)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        Follow::where(function ($q) use ($me, $user) {
            $q->where('follower_id', $me->id)->where('following_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('follower_id', $user->id)->where('following_id', $me->id);
        })->delete();

        return back()->with('success', 'Вы больше не друзья с ' . $user->name);
    }
}