@extends('layouts.app')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink">Лента отзывов</h1>
            <p class="text-autumn-muted mt-1 text-sm sm:text-base">
                Делись впечатлениями о прочитанном
            </p>
        </div>
    </div>

    {{-- Фильтры --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
        <a href="{{ route('reviews.index', ['filter' => 'all']) }}"
           class="px-4 py-2 rounded-lg text-sm whitespace-nowrap transition
                  {{ $filter === 'all'
                        ? 'bg-autumn-green text-white'
                        : 'bg-white border border-autumn-border text-autumn-ink hover:border-autumn-gold' }}">
            Все публичные
        </a>
        <a href="{{ route('reviews.index', ['filter' => 'friends']) }}"
           class="px-4 py-2 rounded-lg text-sm whitespace-nowrap transition
                  {{ $filter === 'friends'
                        ? 'bg-autumn-green text-white'
                        : 'bg-white border border-autumn-border text-autumn-ink hover:border-autumn-gold' }}">
            Для друзей
        </a>
        <a href="{{ route('reviews.index', ['filter' => 'my']) }}"
           class="px-4 py-2 rounded-lg text-sm whitespace-nowrap transition
                  {{ $filter === 'my'
                        ? 'bg-autumn-green text-white'
                        : 'bg-white border border-autumn-border text-autumn-ink hover:border-autumn-gold' }}">
            Мои отзывы
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 px-4 py-3 bg-autumn-success/10 border border-autumn-success/30 text-autumn-success rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($reviews->isEmpty())
        <div class="bg-white border border-autumn-border rounded-xl p-8 sm:p-12 text-center">
            <h2 class="text-xl font-serif text-autumn-ink mb-2">Пока пусто</h2>
            <p class="text-autumn-muted mb-6 text-sm sm:text-base">
                @if ($filter === 'my')
                    Вы ещё не написали ни одного отзыва.
                @elseif ($filter === 'friends')
                    У ваших друзей пока нет отзывов для вас.
                @else
                    Публичных отзывов пока нет.
                @endif
            </p>
            <a href="{{ route('books.index') }}"
               class="inline-block px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                Перейти к библиотеке
            </a>
        </div>
    @else
        <div class="space-y-4 sm:space-y-5">
            @foreach ($reviews as $review)
                <article class="bg-white border border-autumn-border rounded-xl p-4 sm:p-6 hover:border-autumn-gold transition">

                    {{-- Заголовок: пользователь + видимость --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <a href="{{ route('user.profile', $review->user) }}"
                               class="font-semibold text-autumn-ink hover:text-autumn-green transition">
                                {{ $review->user->name }}
                            </a>
                            <p class="text-xs text-autumn-muted mt-0.5">
                                {{ $review->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @php
                            $visibilityLabels = [
                                'public'  => ['label' => 'Публичный',  'color' => 'text-autumn-green bg-autumn-green/10 border-autumn-green/30'],
                                'friends' => ['label' => 'Для друзей', 'color' => 'text-autumn-gold bg-autumn-gold/10 border-autumn-gold/30'],
                                'private' => ['label' => 'Личный',     'color' => 'text-autumn-muted bg-autumn-border/40 border-autumn-border'],
                            ];
                            $v = $visibilityLabels[$review->visibility] ?? $visibilityLabels['public'];
                        @endphp
                        <span class="shrink-0 text-xs px-2 py-1 rounded border {{ $v['color'] }}">
                            {{ $v['label'] }}
                        </span>
                    </div>

                    {{-- Книга --}}
                    <a href="{{ route('books.show', $review->book) }}"
                       class="text-sm text-autumn-muted hover:text-autumn-green transition">
                        О книге: <span class="font-medium text-autumn-ink">{{ $review->book->title }}</span>
                    </a>

                    {{-- Текст отзыва --}}
                    <p class="mt-3 text-autumn-ink whitespace-pre-line break-words">{{ $review->body }}</p>

                    {{-- Подвал: лайки + комментарии + ссылка --}}
                    <div class="mt-4 pt-4 border-t border-autumn-border flex items-center gap-4 text-sm">
                        <form action="{{ route('likes.review', $review) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-1 transition
                                           {{ $review->isLikedBy(auth()->user())
                                                ? 'text-autumn-red hover:text-autumn-red/80'
                                                : 'text-autumn-muted hover:text-autumn-red' }}">
                                <span>{{ $review->isLikedBy(auth()->user()) ? '♥' : '♡' }}</span>
                                <span>{{ $review->likes->count() }}</span>
                            </button>
                        </form>

                        <a href="{{ route('reviews.show', $review) }}"
                           class="text-autumn-muted hover:text-autumn-green transition">
                            Комментарии ({{ $review->comments->count() }})
                        </a>

                        <a href="{{ route('reviews.show', $review) }}"
                           class="ml-auto text-autumn-green hover:text-autumn-green-d transition">
                            Читать →
                        </a>
                    </div>

                </article>
            @endforeach
        </div>

        {{-- Пагинация --}}
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif

@endsection