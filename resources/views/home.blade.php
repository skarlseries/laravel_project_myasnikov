@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <section class="hero">
        <div class="container hero-content">
            <div class="eyebrow">Учебное веб-проект</div>
            <h1>Добро пожаловать на наш сайт!</h1>
            <p class="hero-text">
                Это главная страница приложения, созданного с использованием Laravel и Blade.
                Здесь в дальнейшем можно разместить список новостей и полезных материалов.
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
            <div class="eyebrow">Разделы сайта</div>
            <h2>Начните знакомство</h2>
            <p>Используйте меню в верхней части страницы, чтобы перейти к нужному разделу.</p>
        </div>

        <div class="feature-grid">
            <article class="feature-card">
                <div class="feature-number">01</div>
                <h3>О нас</h3>
                <p>Информация о назначении сайта, его задачах и используемых технологиях.</p>
                <a class="text-link" href="{{ route('about') }}">Перейти к разделу <span aria-hidden="true">→</span></a>
            </article>
            <article class="feature-card">
                <div class="feature-number">02</div>
                <h3>Контакты</h3>
                <p>Контактная информация формируется динамически на основе массива данных.</p>
                <a class="text-link" href="{{ route('contacts') }}">Посмотреть контакты <span aria-hidden="true">→</span></a>
            </article>
        </div>
    </section>
@endsection
