<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BookMark') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-autumn-bg text-autumn-ink min-h-screen flex flex-col">

    <header class="bg-autumn-green-d text-autumn-bg border-b-4 border-autumn-gold shadow-warm-lg">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-xl font-serif font-bold hover:opacity-90 transition">
                <span class="text-2xl text-autumn-gold-l">BM</span>
                <span class="text-autumn-bg">BookMark</span>
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-10 sm:py-16">

        <div class="w-full max-w-md">

            <div class="bg-autumn-card border border-autumn-border rounded-2xl shadow-warm p-6 sm:p-8">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-autumn-muted mt-6">
                BookMark &copy; {{ date('Y') }}
            </p>

        </div>

    </main>

</body>
</html>