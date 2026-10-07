@extends('layouts.app')

@section('content')

    <div class="max-w-3xl mx-auto">

        <a href="{{ route('user.profile', $user) }}"
           class="text-sm text-autumn-muted hover:text-autumn-green transition">
            &larr; К профилю {{ $user->name }}
        </a>

        <h1 class="mt-4 text-2xl sm:text-3xl font-serif font-bold text-autumn-ink mb-6">
            Подписчики
            <span class="text-autumn-muted font-normal text-base sm:text-lg">
                ({{ $followers->total() }})
            </span>
        </h1>

        @if ($followers->isEmpty())
            <div class="bg-white border border-autumn-border rounded-xl p-8 text-center text-autumn-muted">
                Пока никого нет
            </div>
        @else
            <div class="space-y-3">
                @foreach ($followers as $follower)
                    <div class="bg-white border border-autumn-border rounded-xl p-4 flex items-center justify-between gap-3">

                        <div class="min-w-0">
                            <a href="{{ route('user.profile', $follower) }}"
                               class="font-semibold text-autumn-ink hover:text-autumn-green transition break-words">
                                {{ $follower->name }}
                            </a>
                            <p class="text-xs text-autumn-muted mt-0.5">
                                Подписался {{ $follower->pivot->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <a href="{{ route('user.profile', $follower) }}"
                           class="shrink-0 text-sm text-autumn-green hover:text-autumn-green-d transition">
                            Профиль →
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $followers->links() }}
            </div>
        @endif

    </div>

@endsection