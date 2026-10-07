<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    
    /**
     * Общая лента отзывов.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = Review::query()
            ->with(['user', 'book', 'likes', 'comments'])
            ->latest();

        // Применяем фильтры
        match ($filter) {
            'my'      => $query->where('user_id', $me->id),
            'friends' => $query->where('visibility', Review::VISIBILITY_FRIENDS)
                               ->whereIn('user_id', $me->friendIds()),
            default   => $query->public(),
        };

        $reviews = $query->paginate(10)->withQueryString();

        return view('reviews.index', compact('reviews', 'filter'));
    }

    /**
     * Форма создания отзыва на книгу.
     */
    public function create(Book $book)
    {
        // Только владелец книги может написать на неё отзыв
        if ($book->user_id !== Auth::id()) {
            abort(403, 'Отзыв можно написать только на свою книгу');
        }

        return view('reviews.create', compact('book'));
    }

    /**
     * Сохранить отзыв.
     */
    public function store(Request $request, Book $book)
    {
        if ($book->user_id !== Auth::id()) {
            abort(403, 'Отзыв можно написать только на свою книгу');
        }

        $validated = $request->validate([
            'body'       => 'required|string|min:3|max:5000',
            'visibility' => 'required|in:public,friends,private',
        ]);

        /** @var \App\Models\User $me */
        $me = Auth::user();

        $me->reviews()->create([
            'book_id'    => $book->id,
            'body'       => $validated['body'],
            'visibility' => $validated['visibility'],
        ]);

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Отзыв опубликован');
    }

    /**
     * Показать отзыв.
     */
    public function show(Review $review)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();

        // Проверка доступа
        if (! $this->canView($me, $review)) {
            abort(403, 'У вас нет доступа к этому отзыву');
        }

        $review->load(['user', 'book', 'comments.user', 'comments.likes', 'likes']);

        return view('reviews.show', compact('review'));
    }

    /**
     * Форма редактирования.
     */
    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Это не ваш отзыв');
        }

        return view('reviews.edit', compact('review'));
    }

    /**
     * Обновить отзыв.
     */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Это не ваш отзыв');
        }

        $validated = $request->validate([
            'body'       => 'required|string|min:3|max:5000',
            'visibility' => 'required|in:public,friends,private',
        ]);

        $review->update($validated);

        return redirect()
            ->route('reviews.show', $review)
            ->with('success', 'Отзыв обновлён');
    }

    /**
     * Удалить отзыв.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Это не ваш отзыв');
        }

        $review->delete();

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Отзыв удалён');
    }

    /**
     * Проверка, может ли пользователь видеть отзыв.
     */
    private function canView(?User $me, Review $review): bool
    {
        // Свой отзыв видит всегда
        if ($me && $review->user_id === $me->id) {
            return true;
        }

        // Публичный — видят все
        if ($review->visibility === Review::VISIBILITY_PUBLIC) {
            return true;
        }

        // Для друзей — только друзья
        if ($review->visibility === Review::VISIBILITY_FRIENDS) {
            return $me && $me->isFriendsWith($review->user);
        }

        // Приватный — только автор (уже отсекли выше)
        return false;
    }
}