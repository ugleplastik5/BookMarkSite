@extends('layouts.app')

@section('content')

    @php
        $isOwner = auth()->id() === $book->user_id;
    @endphp

    <div class="max-w-3xl mx-auto">

        <a href="{{ route('books.index') }}"
           class="text-sm text-autumn-muted hover:text-autumn-green transition">
            &larr; К библиотеке
        </a>

        <div class="mt-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink break-words">
                    {{ $book->title }}
                </h1>
                @if ($book->author)
                    <p class="text-autumn-muted mt-1 text-sm sm:text-base">{{ $book->author }}</p>
                @endif

                <p class="text-xs text-autumn-muted mt-2">
                    @if ($isOwner)
                        Ваша книга
                    @else
                        Книга пользователя
                        <a href="{{ route('user.profile', $book->user) }}"
                           class="text-autumn-green hover:text-autumn-green-d transition font-medium">
                            {{ $book->user->name }}
                        </a>
                    @endif
                </p>
            </div>

            @if ($isOwner)
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('books.edit', $book) }}"
                       class="flex-1 sm:flex-initial text-center px-4 py-2 bg-autumn-border/50 text-autumn-ink rounded-lg hover:bg-autumn-border transition text-sm whitespace-nowrap">
                        Редактировать
                    </a>
                    <form action="{{ route('books.destroy', $book) }}" method="POST"
                          onsubmit="return confirm('Удалить книгу?')" class="flex-1 sm:flex-initial">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2 bg-autumn-red/10 text-autumn-red rounded-lg hover:bg-autumn-red/20 transition text-sm whitespace-nowrap">
                            Удалить
                        </button>
                    </form>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="mt-6 px-4 py-3 bg-autumn-success/10 border border-autumn-success/30 text-autumn-success rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($book->total_pages)
            <div class="mt-6 sm:mt-8 bg-autumn-card border border-autumn-border rounded-xl p-5 sm:p-6">
                <div class="flex items-end justify-between mb-3">
                    <span class="text-sm text-autumn-muted">
                        @if ($isOwner)
                            Прогресс чтения
                        @else
                            Прогресс чтения {{ $book->user->name }}
                        @endif
                    </span>
                    <span class="text-2xl sm:text-3xl font-serif font-bold text-autumn-gold">
                        {{ $book->progress_percent }}%
                    </span>
                </div>
                <div class="w-full bg-autumn-border/50 rounded-full h-3 overflow-hidden">
                    <div class="bg-autumn-gold h-3 rounded-full transition-all"
                         style="width: {{ $book->progress_percent }}%"></div>
                </div>
                <p class="text-sm text-autumn-muted mt-3">
                    Прочитано {{ $book->current_page }} из {{ $book->total_pages }} страниц
                </p>
            </div>
        @endif

        @if ($isOwner)
            <div class="mt-6 bg-autumn-card border border-autumn-border rounded-xl p-5 sm:p-6">
                <h2 class="text-lg font-serif font-bold text-autumn-ink mb-4">Поставить закладку</h2>

                <form action="{{ route('books.bookmark', $book) }}" method="POST"
                      class="flex flex-col sm:flex-row gap-3 sm:items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm text-autumn-muted mb-1">
                            Я остановился на странице:
                        </label>
                        <input type="number" name="current_page"
                               value="{{ old('current_page', $book->current_page) }}"
                               min="0" max="{{ $book->total_pages ?? 99999 }}"
                               class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold"
                               required>
                    </div>
                    <button type="submit"
                            class="px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                        Сохранить
                    </button>
                </form>
            </div>

            <div class="mt-6 bg-autumn-card border border-autumn-border rounded-xl p-5 sm:p-6">
                <h2 class="text-lg font-serif font-bold text-autumn-ink mb-4">Отзывы и заметки</h2>

                @if ($book->reviews->isEmpty())
                    <p class="text-sm text-autumn-muted mb-4">
                        Вы ещё не писали отзыв об этой книге.
                    </p>
                @else
                    <p class="text-sm text-autumn-muted mb-4">
                        Вы написали {{ $book->reviews->count() }}
                        {{ trans_choice('отзыв|отзыва|отзывов', $book->reviews->count()) }}
                        об этой книге.
                    </p>
                @endif

                <a href="{{ route('reviews.create', $book) }}"
                   class="inline-block px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                    Написать отзыв
                </a>
            </div>
        @endif

        @php
            $visibleReviews = $book->reviews
                ->filter(fn($r) => $r->visibility === 'public'
                    || ($r->visibility === 'friends' && auth()->check() && auth()->user()->isFriendsWith($r->user))
                    || (auth()->check() && $r->user_id === auth()->id()));
        @endphp

        @if ($visibleReviews->isNotEmpty())
            <div class="mt-6">
                <h2 class="text-lg sm:text-xl font-serif font-bold text-autumn-ink mb-4">
                    Отзывы
                </h2>
                <div class="space-y-4">
                    @foreach ($visibleReviews as $review)
                        <article class="bg-autumn-card border border-autumn-border rounded-xl p-4 sm:p-5">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <a href="{{ route('user.profile', $review->user) }}"
                                   class="font-semibold text-autumn-ink hover:text-autumn-green transition">
                                    {{ $review->user->name }}
                                </a>
                                <span class="text-xs text-autumn-muted">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-autumn-ink whitespace-pre-line break-words text-sm sm:text-base">
                                {{ $review->body }}
                            </p>
                            <div class="mt-3 pt-3 border-t border-autumn-border flex items-center gap-4 text-sm">
                                <span class="text-autumn-muted flex items-center gap-1">
                                    <span class="text-base">♥</span>
                                    <span>{{ $review->likes->count() }}</span>
                                </span>
                                <a href="{{ route('reviews.show', $review) }}"
                                   class="ml-auto text-autumn-green hover:text-autumn-green-d transition">
                                    Читать →
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection