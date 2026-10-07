<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Публичный профиль пользователя.
     */
    public function show(User $user)
    {
        /** @var \App\Models\User|null $me */
        $me = Auth::user();

        $reviews = Review::query()
            ->where('user_id', $user->id)
            ->visibleTo($me)
            ->with(['book', 'likes', 'comments'])
            ->latest()
            ->get();

        $booksCount     = $user->books()->count();
        $reviewsCount   = $reviews->count();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        $isFollowing = $me ? $me->isFollowing($user) : false;
        $isFriends   = $me ? $me->isFriendsWith($user) : false;

        return view('profile.show', compact(
            'user', 'reviews',
            'booksCount', 'reviewsCount', 'followersCount', 'followingCount',
            'isFollowing', 'isFriends'
        ));
    }

    /**
     * Книги пользователя.
     */
    public function books(User $user)
    {
        $books = $user->books()
            ->withCount('reviews')
            ->latest()
            ->paginate(20);

        return view('profile.books', compact('user', 'books'));
    }

    /**
     * Подписчики пользователя.
     */
    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(20);

        return view('profile.followers', compact('user', 'followers'));
    }

    /**
     * Подписки пользователя.
     */
    public function following(User $user)
    {
        $following = $user->following()->paginate(20);

        return view('profile.following', compact('user', 'following'));
    }
}