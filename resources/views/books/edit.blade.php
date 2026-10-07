@extends('layouts.app')

@section('content')

    <div class="max-w-2xl mx-auto">

        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-autumn-ink mb-6 break-words">
            Редактировать: {{ $book->title }}
        </h1>

        @if ($errors->any())
            <div class="mb-6 px-4 py-3 bg-autumn-red/10 border border-autumn-red/30 text-autumn-red rounded-lg">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-autumn-border rounded-xl p-5 sm:p-6">
            <form action="{{ route('books.update', $book) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-autumn-ink mb-1">
                        Название <span class="text-autumn-red">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}"
                           class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-autumn-ink mb-1">Автор</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}"
                           class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
                </div>

                <div>
                    <label class="block text-sm font-medium text-autumn-ink mb-1">Всего страниц</label>
                    <input type="number" name="total_pages" value="{{ old('total_pages', $book->total_pages) }}"
                           min="1"
                           class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
                </div>

                <div>
                    <label class="block text-sm font-medium text-autumn-ink mb-1">Текущая страница</label>
                    <input type="number" name="current_page" value="{{ old('current_page', $book->current_page) }}"
                           min="0"
                           class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold">
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                        Обновить
                    </button>
                    <a href="{{ route('books.show', $book) }}"
                       class="text-center px-5 py-2.5 bg-autumn-border/50 text-autumn-ink rounded-lg hover:bg-autumn-border transition">
                        Отмена
                    </a>
                </div>
            </form>
        </div>

    </div>

@endsection