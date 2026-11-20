@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Блог и события</div>
            <h1>Новости</h1>
            <p>Управление публикациями, пагинация и работа с базой данных.</p>

            @can('create', App\Models\Article::class)
                <div class="hero-actions" style="margin-top: 22px;">
                    <a class="button button-primary" href="{{ route('articles.create') }}">+ Добавить новость</a>
                </div>
            @endcan
        </div>
    </section>

    <section class="container content-section">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="form-errors">{{ session('error') }}</div>
        @endif

        <div class="articles-grid">
            @forelse ($articles as $article)
                <article class="article-card">
                    @if ($article->image)
                        <a class="article-preview" href="{{ route('articles.show', $article) }}">
                            <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
                        </a>
                    @endif

                    <div class="article-body">
                        <div class="feature-number">{{ $article->published_at }}</div>
                        <h3>
                            <a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a>
                        </h3>
                        <p>{{ \Illuminate\Support\Str::limit($article->description, 120) }}</p>

                        <div class="article-actions">
                            <a class="text-link" href="{{ route('articles.show', $article) }}">Читать <span>→</span></a>

                            @can('update', $article)
                                <a class="button button-secondary" href="{{ route('articles.edit', $article) }}">Редактировать</a>
                            @endcan

                            @can('delete', $article)
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Удалить эту новость?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button button-danger" type="submit">Удалить</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    Новостей пока нет. Нажмите «Добавить новость» или выполните <code>php artisan db:seed</code>.
                </div>
            @endforelse
        </div>

        <div class="pagination-wrapper">
            {{ $articles->links() }}
        </div>
    </section>
@endsection
