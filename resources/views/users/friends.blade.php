@extends('layouts.app')

@section('content')

    <div class="max-w-3xl mx-auto">

        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink">
                Мои друзья
            </h1>
            <p class="text-autumn-muted mt-1 text-sm sm:text-base">
                Это люди, с которыми у вас взаимная подписка
            </p>
        </div>

        @if ($friends->isEmpty())
            <div class="bg-autumn-card border border-autumn-border rounded-xl p-8 sm:p-12 text-center">
                <p class="text-autumn-muted mb-4">
                    У вас пока нет друзей. Зайдите в раздел «Читатели», подпишитесь на кого-нибудь,
                    и если они подпишутся в ответ — вы станете друзьями.
                </p>
                <a href="{{ route('users.index') }}"
                   class="inline-block px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                    Найти читателей
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($friends as $friend)
                    <div class="bg-autumn-card border border-autumn-border rounded-xl p-4 flex items-center justify-between gap-3">

                        <div class="min-w-0">
                            <a href="{{ route('user.profile', $friend) }}"
                               class="font-semibold text-autumn-ink hover:text-autumn-green transition break-words">
                                {{ $friend->name }}
                            </a>
                            <p class="text-xs text-autumn-muted mt-0.5">
                                Книг: {{ $friend->books()->count() }}
                                &middot;
                                Отзывов: {{ $friend->reviews()->count() }}
                            </p>
                        </div>

                        <div class="flex gap-2 shrink-0">
                            <a href="{{ route('user.profile', $friend) }}"
                               class="text-sm px-3 py-1.5 bg-autumn-border/50 text-autumn-ink rounded-lg hover:bg-autumn-border transition whitespace-nowrap">
                                Профиль
                            </a>
                            <form action="{{ route('follow.unfriend', $friend) }}" method="POST"
                                  onsubmit="return confirm('Удалить {{ $friend->name }} из друзей?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-sm px-3 py-1.5 bg-autumn-red/10 text-autumn-red rounded-lg hover:bg-autumn-red/20 transition whitespace-nowrap">
                                    Удалить
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $friends->links() }}
            </div>
        @endif

    </div>

@endsection