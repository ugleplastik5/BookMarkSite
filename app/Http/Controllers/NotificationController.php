<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Страница со всеми уведомлениями.
     */
    public function index()
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        $notifications = $me->notifications()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Отметить все как прочитанные.
     */
    public function markAllRead()
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        $me->unreadNotifications->markAsRead();

        return back()->with('success', 'Все уведомления прочитаны');
    }

    /**
     * Перейти по уведомлению (отметить прочитанным и редирект).
     */
    public function open(string $id)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        $notification = $me->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('notifications.index'));
    }
}