<x-guest-layout>

    <h1 class="text-2xl font-serif font-bold text-autumn-ink text-center mb-2">
        Регистрация
    </h1>
    <p class="text-sm text-autumn-muted text-center mb-6">
        Создайте аккаунт, чтобы начать свою библиотеку
    </p>

    @if ($errors->any())
        <div class="mb-4 px-3 py-2 bg-autumn-red/10 border border-autumn-red/30 text-autumn-red rounded-lg text-sm">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-autumn-ink mb-1">
                Имя
            </label>
            <input id="name" type="text" name="name"
                   value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-autumn-ink mb-1">
                Email
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   required autocomplete="username"
                   class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-autumn-ink mb-1">
                Пароль
            </label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password"
                   class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-autumn-ink mb-1">
                Повторите пароль
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password"
                   class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
        </div>

        <button type="submit"
                class="w-full px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition font-medium">
            Создать аккаунт
        </button>

        <p class="text-center text-sm text-autumn-muted pt-2">
            Уже есть аккаунт?
            <a href="{{ route('login') }}" class="text-autumn-green hover:text-autumn-green-d transition">
                Войти
            </a>
        </p>

    </form>

</x-guest-layout>