@extends('layouts.app')

@section('content')

    @php
        $me = auth()->user();

        $booksCount     = $me->books()->count();
        $reviewsCount   = $me->reviews()->count();
        $followersCount = $me->followers()->count();
        $followingCount = $me->following()->count();

        $unreadNotifications = $me->unreadNotifications->count();

        $recentBooks = $me->books()->latest()->take(3)->get();
        $recentReviews = $me->reviews()->with('book')->latest()->take(3)->get();

        // Средний прогресс по всем книгам с указанным total_pages
        $booksWithPages = $me->books()->whereNotNull('total_pages')->where('total_pages', '>', 0)->get();
        $avgProgress = $booksWithPages->isEmpty()
            ? 0
            : round($booksWithPages->avg(fn($b) => $b->progress_percent));
    @endphp

    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink">
            Привет, {{ $me->name }}
        </h1>
        <p class="text-autumn-muted mt-1 text-sm sm:text-base">
            Вот краткая сводка вашей библиотеки
        </p>
    </div>

    {{-- Плитки --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <a href="{{ route('books.index') }}"
           class="bg-white border border-autumn-border rounded-xl p-4 sm:p-5 hover:border-autumn-gold transition text-center">
            <div class="text-3xl font-serif font-bold text-autumn-ink">{{ $booksCount }}</div>
            <div class="text-xs text-autumn-muted mt-1">Книг в библиотеке</div>
        </a>

        <a href="{{ route('reviews.index', ['filter' => 'my']) }}"
           class="bg-white border border-autumn-border rounded-xl p-4 sm:p-5 hover:border-autumn-gold transition text-center">
            <div class="text-3xl font-serif font-bold text-autumn-ink">{{ $reviewsCount }}</div>
            <div class="text-xs text-autumn-muted mt-1">Моих отзывов</div>
        </a>

        <a href="{{ route('user.followers', $me) }}"
           class="bg-white border border-autumn-border rounded-xl p-4 sm:p-5 hover:border-autumn-gold transition text-center">
            <div class="text-3xl font-serif font-bold text-autumn-ink">{{ $followersCount }}</div>
            <div class="text-xs text-autumn-muted mt-1">Подписчиков</div>
        </a>

        <a href="{{ route('user.following', $me) }}"
           class="bg-white border border-autumn-border rounded-xl p-4 sm:p-5 hover:border-autumn-gold transition text-center">
            <div class="text-3xl font-serif font-bold text-autumn-ink">{{ $followingCount }}</div>
            <div class="text-xs text-autumn-muted mt-1">Подписок</div>
        </a>
    </div>

    {{-- Средний прогресс --}}
    @if ($booksWithPages->isNotEmpty())
        <div class="mt-6 bg-white border border-autumn-border rounded-xl p-5 sm:p-6">
            <div class="flex items-end justify-between mb-3">
                <span class="text-sm text-autumn-muted">Средний прогресс по всем книгам</span>
                <span class="text-2xl sm:text-3xl font-serif font-bold text-autumn-gold">
                    {{ $avgProgress }}%
                </span>
            </div>
            <div class="w-full bg-autumn-border/50 rounded-full h-3 overflow-hidden">
                <div class="bg-autumn-gold h-3 rounded-full transition-all"
                     style="width: {{ $avgProgress }}%"></div>
            </div>
        </div>
    @endif

    {{-- Уведомления --}}
    @if ($unreadNotifications > 0)
        <a href="{{ route('notifications.index') }}"
           class="mt-6 block bg-autumn-gold/10 border border-autumn-gold/40 rounded-xl p-4 sm:p-5 hover:bg-autumn-gold/15 transition">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <div class="font-medium text-autumn-ink">
                        У вас {{ $unreadNotifications }}
                        {{ trans_choice('непрочитанное уведомление|непрочитанных уведомления|непрочитанных уведомлений', $unreadNotifications) }}
                    </div>
                    <div class="text-sm text-autumn-muted mt-1">
                        Посмотрите, что нового произошло
                    </div>
                </div>
                <span class="text-autumn-gold text-xl shrink-0">&rarr;</span>
            </div>
        </a>
    @endif

    {{-- Последние книги --}}
    @if ($recentBooks->isNotEmpty())
        <section class="mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg sm:text-xl font-serif font-bold text-autumn-ink">Последние книги</h2>
                <a href="{{ route('books.index') }}"
                   class="text-sm text-autumn-green hover:text-autumn-green-d transition">
                    Все книги →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($recentBooks as $book)
                    <a href="{{ route('books.show', $book) }}"
                       class="bg-white border border-autumn-border rounded-xl p-4 hover:border-autumn-gold transition">
                        <h3 class="font-serif font-bold text-autumn-ink break-words">{{ $book->title }}</h3>
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
                                    {{ $book->progress_percent }}%
                                </p>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Последние отзывы --}}
    @if ($recentReviews->isNotEmpty())
        <section class="mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg sm:text-xl font-serif font-bold text-autumn-ink">Мои последние отзывы</h2>
                <a href="{{ route('reviews.index', ['filter' => 'my']) }}"
                   class="text-sm text-autumn-green hover:text-autumn-green-d transition">
                    Все мои отзывы →
                </a>
            </div>

            <div class="space-y-3">
                @foreach ($recentReviews as $review)
                    <a href="{{ route('reviews.show', $review) }}"
                       class="block bg-white border border-autumn-border rounded-xl p-4 hover:border-autumn-gold transition">
                        <p class="text-sm text-autumn-muted mb-1">
                            О книге: <span class="text-autumn-ink font-medium">{{ $review->book->title }}</span>
                        </p>
                        <p class="text-autumn-ink text-sm line-clamp-2 break-words">
                            {{ \Illuminate\Support\Str::limit($review->body, 140) }}
                        </p>
                        <p class="text-xs text-autumn-muted mt-2">
                            {{ $review->created_at->diffForHumans() }}
                        </p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Если вообще пусто --}}
    @if ($recentBooks->isEmpty() && $recentReviews->isEmpty())
        <div class="mt-6 bg-white border border-autumn-border rounded-xl p-8 sm:p-12 text-center">
            <h2 class="text-xl font-serif text-autumn-ink mb-2">Начните с первой книги</h2>
            <p class="text-autumn-muted mb-6 text-sm sm:text-base">
                Добавьте книгу в библиотеку, чтобы отслеживать прогресс чтения.
            </p>
            <a href="{{ route('books.create') }}"
               class="inline-block px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                Добавить книгу
            </a>
        </div>
    @endif

@endsection