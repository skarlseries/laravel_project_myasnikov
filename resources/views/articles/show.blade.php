@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Опубликовано: {{ $article->published_at }}</div>
            <h1>{{ $article->title }}</h1>
        </div>
    </section>

    <section class="container content-section">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('comment_pending'))
            <div class="alert-pending" role="status">{{ session('comment_pending') }}</div>
        @endif

        @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($article->image)
            <div class="gallery-card">
                <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
            </div>
        @endif

        <div class="prose-card" style="margin-bottom: 24px;">
            <p>{{ $article->description }}</p>
        </div>

        <div class="article-actions">
            <a class="button button-secondary" href="{{ route('articles.index') }}">← Назад к списку</a>
            @can('update', $article)
                <a class="button button-primary" href="{{ route('articles.edit', $article) }}">Редактировать</a>
            @endcan
            @can('delete', $article)
                <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Удалить эту новость?');">
                    @csrf
                    @method('DELETE')
                    <button class="button button-danger" type="submit">Удалить</button>
                </form>
            @endcan
        </div>

        <section class="comments-section" id="comments">
            <div class="section-heading">
                <h2>Комментарии ({{ $article->comments->count() }})</h2>
                <p>На странице отображаются только комментарии, прошедшие модерацию.</p>
            </div>

            <div class="comments-list">
                @forelse ($article->comments as $comment)
                    <article class="comment-card">
                        <div class="comment-meta">
                            <strong>{{ $comment->user?->name ?? 'Пользователь удалён' }}</strong>
                            <span>{{ $comment->created_at?->format('d.m.Y H:i') }}</span>
                        </div>
                        <p>{{ $comment->body }}</p>
                    </article>
                @empty
                    <div class="empty-state">Пока нет комментариев, прошедших модерацию. Оставьте первый комментарий.</div>
                @endforelse
            </div>

            <div class="prose-card comment-form-card">
                <h2>Оставить комментарий</h2>
                @auth
                    <form action="{{ route('articles.comments.store', $article) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="body">Текст комментария</label>
                            <textarea
                                class="form-control"
                                id="body"
                                name="body"
                                rows="5"
                                minlength="2"
                                maxlength="2000"
                                placeholder="Введите текст комментария"
                                required
                            >{{ old('body') }}</textarea>
                            <span class="form-hint">От 2 до 2000 символов. Комментарий появится после проверки модератором.</span>
                        </div>
                        <button class="button button-primary" type="submit">Отправить на модерацию</button>
                    </form>
                @else
                    <p>Чтобы оставить комментарий, необходимо <a class="text-link" href="{{ route('login') }}">войти в аккаунт</a>.</p>
                @endauth
            </div>
        </section>
    </section>
@endsection
