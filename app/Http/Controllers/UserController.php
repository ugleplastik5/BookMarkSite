<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Список всех пользователей, кроме себя.
     */
    public function index()
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        $users = User::query()
            ->where('id', '!=', $me->id)
            ->withCount(['books', 'reviews'])
            ->orderBy('name')
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * Мои друзья (взаимные подписки).
     */
    public function friends()
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        $friendIds = $me->friendIds();

        $friends = User::whereIn('id', $friendIds)
            ->orderBy('name')
            ->paginate(20);

        return view('users.friends', compact('friends'));
    }
}