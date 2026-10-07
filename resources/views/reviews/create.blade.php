@extends('layouts.app')

@section('content')

    <div class="max-w-2xl mx-auto">

        <a href="{{ route('books.show', $book) }}"
           class="text-sm text-autumn-muted hover:text-autumn-green transition">
            &larr; К книге
        </a>

        <h1 class="mt-4 text-2xl sm:text-3xl font-serif font-bold text-autumn-ink">
            Написать отзыв
        </h1>
        <p class="text-autumn-muted mt-1 text-sm sm:text-base">
            О книге «{{ $book->title }}»
        </p>

        @if ($errors->any())
            <div class="mt-6 px-4 py-3 bg-autumn-red/10 border border-autumn-red/30 text-autumn-red rounded-lg">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-6 bg-white border border-autumn-border rounded-xl p-5 sm:p-6">
            <form action="{{ route('reviews.store', $book) }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-autumn-ink mb-1">
                        Ваш отзыв <span class="text-autumn-red">*</span>
                    </label>
                    <textarea name="body" rows="8"
                              class="w-full rounded-lg border-autumn-border focus:border-autumn-gold focus:ring-autumn-gold"
                              placeholder="Поделитесь впечатлениями о книге..."
                              required>{{ old('body') }}</textarea>
                    <p class="text-xs text-autumn-muted mt-1">
                        От 3 до 5000 символов
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-autumn-ink mb-2">
                        Кто увидит отзыв
                    </label>

                    <div class="space-y-2">
                        <label class="flex items-start gap-3 p-3 border border-autumn-border rounded-lg cursor-pointer hover:border-autumn-gold transition">
                            <input type="radio" name="visibility" value="public"
                                   {{ old('visibility', 'public') === 'public' ? 'checked' : '' }}
                                   class="mt-1 text-autumn-green focus:ring-autumn-green">
                            <div>
                                <div class="font-medium text-autumn-ink">Публичный</div>
                                <div class="text-sm text-autumn-muted">Виден всем пользователям в общей ленте</div>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3 border border-autumn-border rounded-lg cursor-pointer hover:border-autumn-gold transition">
                            <input type="radio" name="visibility" value="friends"
                                   {{ old('visibility') === 'friends' ? 'checked' : '' }}
                                   class="mt-1 text-autumn-green focus:ring-autumn-green">
                            <div>
                                <div class="font-medium text-autumn-ink">Только для друзей</div>
                                <div class="text-sm text-autumn-muted">Виден пользователям с взаимной подпиской</div>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3 border border-autumn-border rounded-lg cursor-pointer hover:border-autumn-gold transition">
                            <input type="radio" name="visibility" value="private"
                                   {{ old('visibility') === 'private' ? 'checked' : '' }}
                                   class="mt-1 text-autumn-green focus:ring-autumn-green">
                            <div>
                                <div class="font-medium text-autumn-ink">Личный</div>
                                <div class="text-sm text-autumn-muted">Виден только вам, как заметка для себя</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-autumn-green text-white rounded-lg hover:bg-autumn-green-d transition">
                        Опубликовать
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