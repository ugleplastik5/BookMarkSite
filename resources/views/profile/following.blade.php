@extends('layouts.app')

@section('content')

    <div class="max-w-3xl mx-auto">

        <a href="{{ route('user.profile', $user) }}"
           class="text-sm text-autumn-muted hover:text-autumn-green transition">
            &larr; К профилю {{ $user->name }}
        </a>

        <h1 class="mt-4 text-2xl sm:text-3xl font-serif font-bold text-autumn-ink mb-6">
            Подписки
            <span class="text-autumn-muted font-normal text-base sm:text-lg">
                ({{ $following->total() }})
            </span>
        </h1>

        @if ($following->isEmpty())
            <div class="bg-white border border-autumn-border rounded-xl p-8 text-center text-autumn-muted">
                Пока никого нет
            </div>
        @else
            <div class="space-y-3">
                @foreach ($following as $followed)
                    <div class="bg-white border border-autumn-border rounded-xl p-4 flex items-center justify-between gap-3">

                        <div class="min-w-0">
                            <a href="{{ route('user.profile', $followed) }}"
                               class="font-semibold text-autumn-ink hover:text-autumn-green transition break-words">
                                {{ $followed->name }}
                            </a>
                            <p class="text-xs text-autumn-muted mt-0.5">
                                Вы подписаны {{ $followed->pivot->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <a href="{{ route('user.profile', $followed) }}"
                           class="shrink-0 text-sm text-autumn-green hover:text-autumn-green-d transition">
                            Профиль →
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $following->links() }}
            </div>
        @endif

    </div>

@endsection