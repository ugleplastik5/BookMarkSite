@extends('layouts.app')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink">Моя библиотека</h1>
            <p class="text-autumn-muted mt-1 text-sm sm:text-base">
                {{ $books->count() }} {{ trans_choice('книга|книги|книг', $books->count()) }}
            </p>
        </div>

        <a href="{{ route('books.create') }}"
           class="inline-block text-center px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition shadow-sm">
            Добавить книгу
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 px-4 py-3 bg-autumn-success/10 border border-autumn-success/30 text-autumn-success rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($books->isEmpty())
        <div class="bg-white border border-autumn-border rounded-xl p-8 sm:p-12 text-center">
            <h2 class="text-xl font-serif text-autumn-ink mb-2">Пока пусто</h2>
            <p class="text-autumn-muted mb-6 text-sm sm:text-base">
                Добавьте первую книгу в свою библиотеку, и она появится здесь.
            </p>
            <a href="{{ route('books.create') }}"
               class="inline-block px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                Добавить первую книгу
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach ($books as $book)
                <a href="{{ route('books.show', $book) }}"
                   class="bg-white border border-autumn-border rounded-xl p-4 sm:p-5 hover:border-autumn-gold hover:shadow-md transition group">

                    <h3 class="text-lg font-serif font-bold text-autumn-ink group-hover:text-autumn-green transition break-words">
                        {{ $book->title }}
                    </h3>

                    @if ($book->author)
                        <p class="text-sm text-autumn-muted mt-1 break-words">{{ $book->author }}</p>
                    @endif

                    @if ($book->total_pages)
                        <div class="mt-4">
                            <div class="w-full bg-autumn-border/50 rounded-full h-2 overflow-hidden">
                                <div class="bg-autumn-gold h-2 rounded-full transition-all"
                                     style="width: {{ $book->progress_percent }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-2 text-xs text-autumn-muted">
                                <span>{{ $book->current_page }} / {{ $book->total_pages }} стр.</span>
                                <span class="font-semibold text-autumn-gold">{{ $book->progress_percent }}%</span>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-autumn-muted mt-3 italic">Всего страниц не указано</p>
                    @endif

                </a>
            @endforeach
        </div>
    @endif

@endsection