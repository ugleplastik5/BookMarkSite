<x-guest-layout>

    <h1 class="text-2xl font-serif font-bold text-autumn-ink text-center mb-2">
        Вход
    </h1>
    <p class="text-sm text-autumn-muted text-center mb-6">
        Рады видеть вас снова
    </p>

    {{-- Ошибки валидации --}}
    @if ($errors->any())
        <div class="mb-4 px-3 py-2 bg-autumn-red/10 border border-autumn-red/30 text-autumn-red rounded-lg text-sm">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Статус (например, "проверьте почту") --}}
    @if (session('status'))
        <div class="mb-4 px-3 py-2 bg-autumn-success/10 border border-autumn-success/30 text-autumn-success rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-autumn-ink mb-1">
                Email
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-autumn-ink mb-1">
                Пароль
            </label>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password"
                   class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember"
                       class="rounded border-autumn-border text-autumn-green focus:ring-autumn-green">
                <span class="text-autumn-muted">Запомнить меня</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-autumn-green hover:text-autumn-green-d transition">
                    Забыли пароль?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition font-medium">
            Войти
        </button>

        <p class="text-center text-sm text-autumn-muted pt-2">
            Нет аккаунта?
            <a href="{{ route('register') }}" class="text-autumn-green hover:text-autumn-green-d transition">
                Зарегистрироваться
            </a>
        </p>

    </form>

</x-guest-layout>