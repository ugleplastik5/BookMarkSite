@extends('layouts.app')

@section('content')

    <div class="max-w-3xl mx-auto">

        {{-- Шапка профиля --}}
        <div class="bg-white border border-autumn-border rounded-xl p-5 sm:p-6">

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink break-words">
                        {{ $user->name }}
                    </h1>
                    <p class="text-sm text-autumn-muted mt-1">
                        С нами с {{ $user->created_at->format('d.m.Y') }}
                    </p>
                </div>

                @if (auth()->id() !== $user->id)
                    <div class="flex flex-col sm:flex-row gap-2 shrink-0">

                        @if ($isFriends)
                            <span class="text-center px-4 py-2 text-sm rounded-lg bg-autumn-gold/15 text-autumn-gold border border-autumn-gold/40">
                                Друзья
                            </span>
                            <form action="{{ route('follow.unfriend', $user) }}" method="POST"
                                  onsubmit="return confirm('Удалить из друзей?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full px-4 py-2 text-sm rounded-lg bg-autumn-red/10 text-autumn-red hover:bg-autumn-red/20 transition">
                                    Удалить из друзей
                                </button>
                            </form>
                        @elseif ($isFollowing)
                            <span class="text-center px-4 py-2 text-sm rounded-lg bg-autumn-border/60 text-autumn-muted">
                                Вы подписаны
                            </span>
                            <form action="{{ route('follow.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full px-4 py-2 text-sm rounded-lg bg-autumn-border/50 text-autumn-ink hover:bg-autumn-border transition">
                                    Отписаться
                                </button>
                            </form>
                        @else
                            <form action="{{ route('follow.store', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full px-4 py-2 text-sm rounded-lg bg-autumn-green text-white hover:bg-autumn-green-d transition">
                                    Подписаться
                                </button>
                            </form>
                        @endif

                    </div>
                @else
                    <a href="{{ route('profile.edit') }}"
                       class="text-center px-4 py-2 text-sm rounded-lg bg-autumn-border/50 text-autumn-ink hover:bg-autumn-border transition shrink-0">
                        Редактировать профиль
                    </a>
                @endif
            </div>

            {{-- Статистика --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-6 pt-6 border-t border-autumn-border">
                <a href="{{ route('books.index') }}"
                   class="text-center hover:bg-autumn-border/30 rounded-lg py-3 transition">
                    <div class="text-2xl font-serif font-bold text-autumn-ink">{{ $booksCount }}</div>
                    <div class="text-xs text-autumn-muted mt-1">Книг</div>
                </a>

                <a href="#reviews"
                   class="text-center hover:bg-autumn-border/30 rounded-lg py-3 transition">
                    <div class="text-2xl font-serif font-bold text-autumn-ink">{{ $reviewsCount }}</div>
                    <div class="text-xs text-autumn-muted mt-1">Отзывов</div>
                </a>

                <a href="{{ route('user.followers', $user) }}"
                   class="text-center hover:bg-autumn-border/30 rounded-lg py-3 transition">
                    <div class="text-2xl font-serif font-bold text-autumn-ink">{{ $followersCount }}</div>
                    <div class="text-xs text-autumn-muted mt-1">Подписчиков</div>
                </a>

                <a href="{{ route('user.following', $user) }}"
                   class="text-center hover:bg-autumn-border/30 rounded-lg py-3 transition">
                    <div class="text-2xl font-serif font-bold text-autumn-ink">{{ $followingCount }}</div>
                    <div class="text-xs text-autumn-muted mt-1">Подписок</div>
                </a>
            </div>

        </div>

        {{-- Отзывы пользователя --}}
        <section class="mt-6" id="reviews">
            <h2 class="text-lg sm:text-xl font-serif font-bold text-autumn-ink mb-4">
                Отзывы
            </h2>

            @if ($reviews->isEmpty())
                <div class="bg-white border border-autumn-border rounded-xl p-6 text-center text-autumn-muted text-sm">
                    @if (auth()->id() === $user->id)
                        Вы ещё не написали ни одного отзыва.
                    @else
                        Пользователь пока не написал ни одного отзыва, доступного вам.
                    @endif
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($reviews as $review)
                        <article class="bg-white border border-autumn-border rounded-xl p-4 sm:p-5 hover:border-autumn-gold transition">

                            <div class="flex items-start justify-between gap-3 mb-2">
                                <a href="{{ route('books.show', $review->book) }}"
                                   class="text-sm text-autumn-muted hover:text-autumn-green transition min-w-0">
                                    О книге: <span class="font-medium text-autumn-ink">{{ $review->book->title }}</span>
                                </a>

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

                            <p class="text-autumn-ink whitespace-pre-line break-words text-sm sm:text-base">
                                {{ $review->body }}
                            </p>

                            <div class="mt-4 pt-3 border-t border-autumn-border flex items-center gap-4 text-sm">
                                <span class="text-autumn-muted flex items-center gap-1">
                                    <span class="text-base">♥</span>
                                    <span>{{ $review->likes->count() }}</span>
                                </span>

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
            @endif
        </section>

    </div>

@endsection