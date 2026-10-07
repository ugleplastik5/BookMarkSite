@extends('layouts.app')

@section('content')

    <div class="max-w-3xl mx-auto">

        <a href="{{ route('user.profile', $user) }}"
           class="text-sm text-autumn-muted hover:text-autumn-green transition">
            &larr; К профилю {{ $user->name }}
        </a>

        <h1 class="mt-4 text-2xl sm:text-3xl font-serif font-bold text-autumn-ink mb-6">
            Книги {{ $user->name }}
            <span class="text-autumn-muted font-normal text-base sm:text-lg">
                ({{ $books->total() }})
            </span>
        </h1>

        @if ($books->isEmpty())
            <div class="bg-autumn-card border border-autumn-border rounded-xl p-8 text-center text-autumn-muted">
                У пользователя пока нет книг
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($books as $book)
                    <a href="{{ route('books.show', $book) }}"
                       class="bg-autumn-card border border-autumn-border rounded-xl p-5 hover:border-autumn-gold transition">

                        <h3 class="font-serif font-bold text-autumn-ink break-words">
                            {{ $book->title }}
                        </h3>

                        @if ($book->author)
                            <p class="text-sm text-autumn-muted mt-1 break-words">{{ $book->author }}</p>
                        @endif

                        @if ($book->total_pages)
                            <div class="mt-3">
                                <div class="w-full bg-autumn-border/50 rounded-full h-2 overflow-hidden">
                                    <div class="bg-autumn-gold h-2 rounded-full"
                                         style="width: {{ $book->progress_percent }}%"></div>
                                </div>
                                <p class="text-xs text-autumn-muted mt-1.5">
                                    {{ $book->progress_percent }}% — {{ $book->current_page }} / {{ $book->total_pages }} стр.
                                </p>
                            </div>
                        @endif

                        @if ($book->reviews_count > 0)
                            <p class="text-xs text-autumn-muted mt-3">
                                {{ $book->reviews_count }}
                                {{ trans_choice('отзыв|отзыва|отзывов', $book->reviews_count) }}
                            </p>
                        @endif

                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $books->links() }}
            </div>
        @endif

    </div>

@endsection