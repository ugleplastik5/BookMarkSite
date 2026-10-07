@extends('layouts.app')

@section('content')

    <div class="max-w-3xl mx-auto">

        <a href="{{ route('reviews.index') }}"
           class="text-sm text-autumn-muted hover:text-autumn-green transition">
            &larr; К ленте отзывов
        </a>

        @if (session('success'))
            <div class="mt-4 px-4 py-3 bg-autumn-success/10 border border-autumn-success/30 text-autumn-success rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Карточка отзыва --}}
        <article class="mt-4 bg-white border border-autumn-border rounded-xl p-5 sm:p-6">

            {{-- Шапка: автор + видимость --}}
            <div class="flex items-start justify-between gap-3 mb-4">
                <div class="min-w-0">
                    <a href="{{ route('user.profile', $review->user) }}"
                       class="font-semibold text-autumn-ink hover:text-autumn-green transition">
                        {{ $review->user->name }}
                    </a>
                    <p class="text-xs text-autumn-muted mt-0.5">
                        {{ $review->created_at->format('d.m.Y H:i') }}
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
               class="inline-block text-sm text-autumn-muted hover:text-autumn-green transition mb-4">
                О книге: <span class="font-medium text-autumn-ink">{{ $review->book->title }}</span>
                @if ($review->book->author)
                    — {{ $review->book->author }}
                @endif
            </a>

            {{-- Текст отзыва --}}
            <div class="text-autumn-ink whitespace-pre-line break-words text-base leading-relaxed">
                {{ $review->body }}
            </div>

            {{-- Лайк --}}
            <div class="mt-6 pt-4 border-t border-autumn-border flex items-center gap-4 text-sm">
                <form action="{{ route('likes.review', $review) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-1.5 transition
                                   {{ $review->isLikedBy(auth()->user())
                                        ? 'text-autumn-red hover:text-autumn-red/80'
                                        : 'text-autumn-muted hover:text-autumn-red' }}">
                        <span class="text-base">{{ $review->isLikedBy(auth()->user()) ? '♥' : '♡' }}</span>
                        <span>{{ $review->likes->count() }}</span>
                    </button>
                </form>
            </div>

            {{-- Кнопки автора --}}
            @if ($review->user_id === auth()->id())
                <div class="mt-4 pt-4 border-t border-autumn-border flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('reviews.edit', $review) }}"
                       class="text-center px-4 py-2 bg-autumn-border/50 text-autumn-ink rounded-lg hover:bg-autumn-border transition text-sm">
                        Редактировать
                    </a>
                    <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                          onsubmit="return confirm('Удалить отзыв?')"
                          class="flex-1 sm:flex-initial">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2 bg-autumn-red/10 text-autumn-red rounded-lg hover:bg-autumn-red/20 transition text-sm">
                            Удалить
                        </button>
                    </form>
                </div>
            @endif

        </article>

        {{-- Комментарии --}}
        <section class="mt-6">
            <h2 class="text-lg sm:text-xl font-serif font-bold text-autumn-ink mb-4">
                Комментарии
                @if ($review->comments->count())
                    <span class="text-autumn-muted font-normal text-base">
                        ({{ $review->comments->count() }})
                    </span>
                @endif
            </h2>

            {{-- Форма добавления --}}
            <form action="{{ route('comments.store', $review) }}" method="POST"
                  class="bg-white border border-autumn-border rounded-xl p-4 sm:p-5 mb-5">
                @csrf
                <label class="block text-sm font-medium text-autumn-ink mb-2">
                    Оставить комментарий
                </label>
                <textarea name="body" rows="3"
                          class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold"
                          placeholder="Ваш комментарий..."
                          required>{{ old('body') }}</textarea>

                @error('body')
                    <p class="text-sm text-autumn-red mt-2">{{ $message }}</p>
                @enderror

                <button type="submit"
                        class="mt-3 px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                    Отправить
                </button>
            </form>

            {{-- Список комментариев --}}
            @if ($review->comments->isEmpty())
                <div class="bg-white border border-autumn-border rounded-xl p-6 text-center text-autumn-muted text-sm">
                    Комментариев пока нет. Будьте первым.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($review->comments as $comment)
                        <div class="bg-white border border-autumn-border rounded-xl p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ route('user.profile', $comment->user) }}"
                                       class="font-semibold text-autumn-ink hover:text-autumn-green transition text-sm">
                                        {{ $comment->user->name }}
                                    </a>
                                    <p class="text-xs text-autumn-muted mt-0.5">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                @if ($comment->user_id === auth()->id())
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                          onsubmit="return confirm('Удалить комментарий?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs text-autumn-muted hover:text-autumn-red transition">
                                            Удалить
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <p class="mt-3 text-autumn-ink whitespace-pre-line break-words text-sm">
                                {{ $comment->body }}
                            </p>

                            {{-- Лайк комментария --}}
                            <div class="mt-3 pt-3 border-t border-autumn-border/60">
                                <form action="{{ route('likes.comment', $comment) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-1.5 text-xs transition
                                                   {{ $comment->isLikedBy(auth()->user())
                                                        ? 'text-autumn-red hover:text-autumn-red/80'
                                                        : 'text-autumn-muted hover:text-autumn-red' }}">
                                        <span class="text-sm">{{ $comment->isLikedBy(auth()->user()) ? '♥' : '♡' }}</span>
                                        <span>{{ $comment->likes->count() }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>

@endsection