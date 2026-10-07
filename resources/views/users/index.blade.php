@extends('layouts.app')

@section('content')

    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink">Читатели</h1>
        <p class="text-autumn-muted mt-1 text-sm sm:text-base">
            Находите людей, подписывайтесь и читайте их отзывы
        </p>
    </div>

    @if ($users->isEmpty())
        <div class="bg-white border border-autumn-border rounded-xl p-8 sm:p-12 text-center">
            <p class="text-autumn-muted">Пока никого нет. Пригласите друзей!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach ($users as $user)
                <div class="bg-white border border-autumn-border rounded-xl p-5 hover:border-autumn-gold transition">
                    <a href="{{ route('user.profile', $user) }}"
                       class="block text-lg font-serif font-bold text-autumn-ink hover:text-autumn-green transition break-words">
                        {{ $user->name }}
                    </a>

                    <p class="text-xs text-autumn-muted mt-1">
                        Книг: {{ $user->books_count }}
                        &middot;
                        Отзывов: {{ $user->reviews_count }}
                    </p>

                    <a href="{{ route('user.profile', $user) }}"
                       class="mt-4 inline-block text-sm text-autumn-green hover:text-autumn-green-d transition">
                        Открыть профиль →
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif

@endsection