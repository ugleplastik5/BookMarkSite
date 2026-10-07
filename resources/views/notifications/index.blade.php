@extends('layouts.app')

@section('content')

    <div class="max-w-3xl mx-auto">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink">Уведомления</h1>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-sm text-autumn-green hover:text-autumn-green-d transition">
                        Отметить всё прочитанным
                    </button>
                </form>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-autumn-success/10 border border-autumn-success/30 text-autumn-success rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($notifications->isEmpty())
            <div class="bg-white border border-autumn-border rounded-xl p-8 sm:p-12 text-center">
                <p class="text-autumn-muted">Уведомлений пока нет</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach ($notifications as $notification)
                    <a href="{{ route('notifications.open', $notification->id) }}"
                       class="block bg-white border rounded-xl p-4 hover:border-autumn-gold transition
                              {{ $notification->read_at
                                    ? 'border-autumn-border'
                                    : 'border-autumn-gold/60 bg-autumn-gold/5' }}">

                        <div class="flex items-start gap-3">
                            <span class="shrink-0 mt-1.5 w-2 h-2 rounded-full
                                         {{ $notification->read_at ? 'bg-transparent' : 'bg-autumn-gold' }}"></span>

                            <div class="min-w-0 flex-1">
                                <p class="text-autumn-ink text-sm sm:text-base break-words">
                                    {{ $notification->data['message'] ?? 'Новое уведомление' }}
                                </p>
                                <p class="text-xs text-autumn-muted mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>

@endsection