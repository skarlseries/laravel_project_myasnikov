@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <section class="hero">
        <div class="container hero-content">
            <div class="eyebrow">Учебный веб-проект</div>
            <h1>Добро пожаловать на наш сайт!</h1>
            <p class="hero-text">
                Это главная страница приложения, созданного с использованием Laravel и Blade.
                Материалы ниже читаются из файла articles.json и выводятся на страницу.
            </p>
            <div class="hero-actions">
                <a class="button button-primary" href="{{ route('about') }}">Узнать о проекте</a>
                <a class="button button-secondary" href="{{ route('contacts') }}">Связаться с нами</a>
            </div>
        </div>
        <div class="hero-decoration" aria-hidden="true">
            <div class="decoration-card decoration-card-one">Blade</div>
            <div class="decoration-card decoration-card-two">Route</div>
            <div class="decoration-dot"></div>
        </div>
    </section>

    <section class="container intro-section">
        <div class="section-heading">
            <div class="eyebrow">Данные из JSON</div>
            <h2>Статьи</h2>
            <p>Нажмите на превью, чтобы открыть полное изображение на странице галереи.</p>
        </div>

        @if (count($articles) === 0)
            <div class="empty-state">В файле articles.json пока нет материалов.</div>
        @else
            <div class="articles-grid">
                @foreach ($articles as $article)
                    <article class="article-card">
                        <a class="article-preview" href="{{ route('gallery', $article['id']) }}">
                            <img src="{{ asset($article['preview_image']) }}" alt="{{ $article['title'] }}">
                        </a>
                        <div class="article-body">
                            <h3>{{ $article['title'] }}</h3>
                            <p>{{ $article['description'] }}</p>
                            <a class="text-link" href="{{ route('gallery', $article['id']) }}">Открыть в галерее <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
