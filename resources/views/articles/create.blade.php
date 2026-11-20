@extends('layouts.app')

@section('title', 'Создание новости')

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Управление контентом</div>
            <h1>Добавить новость</h1>
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

            <form action="{{ route('articles.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="title">Заголовок</label>
                    <input class="form-control" type="text" id="title" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="published_at">Дата публикации</label>
                    <input class="form-control" type="date" id="published_at" name="published_at" value="{{ old('published_at', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Путь к изображению (необязательно)</label>
                    <input class="form-control" type="text" id="image" name="image" value="{{ old('image', '/images/full.jpg') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Текст новости</label>
                    <textarea class="form-control" id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                </div>

                <div class="article-actions">
                    <button class="button button-primary" type="submit">Сохранить новость</button>
                    <a class="button button-secondary" href="{{ route('articles.index') }}">Отмена</a>
                </div>
            </form>
        </div>
    </section>
@endsection
