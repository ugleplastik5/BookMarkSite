<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookMark — твоя личная библиотека</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-autumn-bg text-autumn-ink min-h-screen flex flex-col">

    <header class="bg-autumn-card border-b border-autumn-border shadow-warm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-xl font-serif font-bold text-autumn-green">
                <span class="text-2xl">BM</span>
                <span>BookMark</span>
            </a>

            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('login') }}"
                   class="px-3 py-1.5 text-autumn-ink hover:text-autumn-green transition font-medium">
                    Вход
                </a>
                <a href="{{ route('register') }}"
                   class="px-3 py-1.5 bg-autumn-green text-white rounded hover:bg-autumn-green-d transition font-semibold shadow-sm">
                    Регистрация
                </a>
            </nav>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-12 sm:py-20">
        <div class="max-w-3xl mx-auto text-center">

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-serif font-bold text-autumn-ink leading-tight">
                Book<span class="text-autumn-gold">Mark</span>
            </h1>

            <p class="mt-6 sm:mt-8 text-lg sm:text-xl text-autumn-muted leading-relaxed font-medium">
                Личная библиотека для тех, кто любит читать.
                Отслеживайте прогресс, оставляйте заметки, делитесь отзывами с близкими.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register') }}"
                   class="px-7 py-3.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition font-semibold shadow-warm hover:shadow-warm-lg">
                    Начать читать
                </a>
                <a href="{{ route('login') }}"
                   class="px-7 py-3.5 bg-autumn-card border border-autumn-border text-autumn-ink rounded-lg hover:border-autumn-gold hover:shadow-warm transition font-semibold">
                    Войти
                </a>
            </div>

            <div class="mt-20 grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">

                <div class="bg-autumn-card border border-autumn-border rounded-xl p-6 shadow-warm hover:shadow-warm-lg transition">
                    <div class="text-4xl font-serif font-bold text-autumn-gold mb-3">01</div>
                    <h3 class="font-serif font-bold text-xl text-autumn-ink mb-2">Своя библиотека</h3>
                    <p class="text-sm text-autumn-muted leading-relaxed">
                        Добавляйте книги, которые читаете. Указывайте автора, количество страниц и обложку.
                    </p>
                </div>

                <div class="bg-autumn-card border border-autumn-border rounded-xl p-6 shadow-warm hover:shadow-warm-lg transition">
                    <div class="text-4xl font-serif font-bold text-autumn-gold mb-3">02</div>
                    <h3 class="font-serif font-bold text-xl text-autumn-ink mb-2">Закладки и прогресс</h3>
                    <p class="text-sm text-autumn-muted leading-relaxed">
                        Отмечайте, где остановились. Смотрите процент прочитанного по каждой книге.
                    </p>
                </div>

                <div class="bg-autumn-card border border-autumn-border rounded-xl p-6 shadow-warm hover:shadow-warm-lg transition">
                    <div class="text-4xl font-serif font-bold text-autumn-gold mb-3">03</div>
                    <h3 class="font-serif font-bold text-xl text-autumn-ink mb-2">Отзывы и друзья</h3>
                    <p class="text-sm text-autumn-muted leading-relaxed">
                        Делитесь впечатлениями — публично, только с друзьями или для себя.
                    </p>
                </div>

            </div>

        </div>
    </main>

    <footer class="border-t border-autumn-border py-6 text-center text-sm text-autumn-muted">
        BookMark &copy; {{ date('Y') }} — твоя личная библиотека
    </footer>

</body>
</html>