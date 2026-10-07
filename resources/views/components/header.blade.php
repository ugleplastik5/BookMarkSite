<header class="bg-autumn-green-d text-autumn-bg border-b-4 border-autumn-gold shadow-warm-lg relative z-40">
    <div class="max-w-6xl mx-auto px-3 sm:px-4 py-3 flex items-center justify-between gap-2">

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 text-lg sm:text-xl font-serif font-bold hover:opacity-90 transition shrink-0">
            <span class="text-xl sm:text-2xl text-autumn-gold-l">BM</span>
            <span class="hidden xs:inline text-autumn-bg">BookMark</span>
        </a>

        <nav class="flex items-center gap-1 sm:gap-3 text-xs sm:text-sm">
            @auth
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                    $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get();
                @endphp

                <a href="{{ route('dashboard') }}"
                   class="px-2 py-1.5 text-autumn-bg/90 hover:text-autumn-gold-l transition whitespace-nowrap font-medium"
                   title="Мой кабинет">
                    <span class="hidden sm:inline">Кабинет</span>
                    <span class="sm:hidden">Каб.</span>
                </a>

                <a href="{{ route('books.index') }}"
                   class="px-2 py-1.5 text-autumn-bg/90 hover:text-autumn-gold-l transition whitespace-nowrap font-medium"
                   title="Моя библиотека">
                    <span class="hidden sm:inline">Моя библиотека</span>
                    <span class="sm:hidden">Книги</span>
                </a>

                <a href="{{ route('reviews.index') }}"
                   class="px-2 py-1.5 text-autumn-bg/90 hover:text-autumn-gold-l transition whitespace-nowrap font-medium hidden sm:inline-block"
                   title="Лента отзывов">
                    Лента
                </a>

                <a href="{{ route('users.index') }}"
                   class="px-2 py-1.5 text-autumn-bg/90 hover:text-autumn-gold-l transition whitespace-nowrap font-medium hidden sm:inline-block"
                   title="Читатели">
                    Читатели
                </a>

                <a href="{{ route('books.create') }}"
                   class="px-2 py-1.5 text-autumn-bg/90 hover:text-autumn-gold-l transition whitespace-nowrap font-medium"
                   title="Добавить книгу">
                    <span class="hidden sm:inline">Добавить</span>
                    <span class="sm:hidden">+</span>
                </a>

                {{-- Колокольчик --}}
                <div class="relative" id="notifications-dropdown">
                    <button type="button"
                            onclick="document.getElementById('notifications-dropdown').classList.toggle('open')"
                            class="relative px-2 py-1.5 text-autumn-bg/90 hover:text-autumn-gold-l transition focus:outline-none"
                            title="Уведомления">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>

                        @if ($unreadCount > 0)
                            <span class="absolute top-0 right-0 sm:top-0.5 sm:right-0.5 bg-autumn-red text-white text-[10px] leading-none rounded-full min-w-[16px] h-[16px] flex items-center justify-center font-bold px-1 ring-2 ring-autumn-green-d">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div class="notification-panel absolute right-0 mt-2 w-80 max-w-[calc(100vw-1.5rem)] bg-autumn-card text-autumn-ink border border-autumn-border rounded-xl shadow-warm-lg overflow-hidden">
                        <div class="px-4 py-3 border-b border-autumn-border flex items-center justify-between bg-autumn-bg-2/50">
                            <span class="font-serif font-bold text-autumn-ink text-sm">Уведомления</span>
                            @if ($unreadCount > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs text-autumn-green hover:text-autumn-green-d transition font-semibold">
                                        Прочитать всё
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="max-h-96 overflow-y-auto">
                            @if ($recentNotifications->isEmpty())
                                <div class="px-4 py-6 text-center text-sm text-autumn-muted">
                                    Уведомлений пока нет
                                </div>
                            @else
                                @foreach ($recentNotifications as $notification)
                                    <a href="{{ route('notifications.open', $notification->id) }}"
                                       class="block px-4 py-3 border-b border-autumn-border/60 last:border-b-0 hover:bg-autumn-bg-2/60 transition
                                              {{ $notification->read_at ? '' : 'bg-autumn-gold/5' }}">
                                        <div class="flex items-start gap-2">
                                            @if (! $notification->read_at)
                                                <span class="shrink-0 mt-1.5 w-1.5 h-1.5 rounded-full bg-autumn-gold"></span>
                                            @else
                                                <span class="shrink-0 mt-1.5 w-1.5 h-1.5"></span>
                                            @endif

                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm text-autumn-ink break-words leading-snug font-medium">
                                                    {{ $notification->data['message'] ?? 'Новое уведомление' }}
                                                </p>
                                                <p class="text-xs text-autumn-muted mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>

                        <a href="{{ route('notifications.index') }}"
                           class="block px-4 py-3 text-center text-sm text-autumn-green hover:bg-autumn-bg-2/60 transition border-t border-autumn-border font-semibold">
                            Все уведомления →
                        </a>
                    </div>
                </div>

                <a href="{{ route('user.profile', auth()->user()) }}"
                   class="px-2 py-1.5 text-autumn-bg/80 hover:text-autumn-gold-l transition whitespace-nowrap font-medium"
                   title="Мой профиль">
                    Профиль
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-2 py-1.5 text-autumn-bg/80 hover:text-autumn-red transition whitespace-nowrap font-medium"
                            title="Выйти">
                        Выйти
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="px-2 py-1.5 text-autumn-bg/90 hover:text-autumn-gold-l transition font-medium">
                    Вход
                </a>
                <a href="{{ route('register') }}"
                   class="px-3 py-1.5 bg-autumn-gold text-autumn-green-d rounded hover:bg-autumn-gold-l transition whitespace-nowrap font-bold shadow-sm">
                    Регистрация
                </a>
            @endauth
        </nav>

    </div>
</header>

<style>
    #notifications-dropdown .notification-panel {
        display: none;
    }
    #notifications-dropdown.open .notification-panel {
        display: block;
        animation: dropdown-fade 0.15s ease-out;
    }

    @keyframes dropdown-fade {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('notifications-dropdown');
        if (!dropdown) return;

        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });
</script>