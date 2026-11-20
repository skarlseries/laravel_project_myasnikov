@extends('layouts.app')

@section('title', 'Редактирование новости')

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Управление контентом</div>
            <h1>Редактировать новость</h1>
        </div>
    </section>

    <section class="container content-section">
        <div class="prose-card auth-card" style="max-width: 680px;">
            @if ($errors->any())
                <div class="form-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('articles.update', $article) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="title">Заголовок</label>
                    <input class="form-control" type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="published_at">Дата публикации</label>
                    <input class="form-control" type="date" id="published_at" name="published_at" value="{{ old('published_at', $article->published_at) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Путь к изображению</label>
                    <input class="form-control" type="text" id="image" name="image" value="{{ old('image', $article->image) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Текст новости</label>
                    <textarea class="form-control" id="description" name="description" rows="6" required>{{ old('description', $article->description) }}</textarea>
                </div>

                <div class="article-actions">
                    <button class="button button-primary" type="submit">Обновить новость</button>
                    <a class="button button-secondary" href="{{ route('articles.index') }}">Отмена</a>
                </div>
            </form>
        </div>
    </section>
@endsection
