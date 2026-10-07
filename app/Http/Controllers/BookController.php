<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $books = $user->books()->latest()->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'nullable|string|max:255',
            'total_pages' => 'nullable|integer|min:1',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->books()->create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Книга добавлена в библиотеку');
    }

    /**
     * Показать книгу — можно любую, но редактировать только свои.
     */
    public function show(Book $book)
    {
        $book->load('reviews.user', 'user');

        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $this->authorizeBook($book);

        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorizeBook($book);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author'       => 'nullable|string|max:255',
            'total_pages'  => 'nullable|integer|min:1',
            'current_page' => 'nullable|integer|min:0',
        ]);

        if (isset($validated['total_pages'], $validated['current_page'])
            && $validated['current_page'] > $validated['total_pages']) {
            $validated['current_page'] = $validated['total_pages'];
        }

        $book->update($validated);

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Книга обновлена');
    }

    public function destroy(Book $book)
    {
        $this->authorizeBook($book);

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Книга удалена');
    }

    public function bookmark(Request $request, Book $book)
    {
        $this->authorizeBook($book);

        $validated = $request->validate([
            'current_page' => 'required|integer|min:0',
        ]);

        if ($book->total_pages && $validated['current_page'] > $book->total_pages) {
            $validated['current_page'] = $book->total_pages;
        }

        $book->update($validated);

        return back()->with('success', "Закладка: страница {$book->current_page}");
    }

    private function authorizeBook(Book $book): void
    {
        if ($book->user_id !== Auth::id()) {
            abort(403, 'Это не ваша книга');
        }
    }
}