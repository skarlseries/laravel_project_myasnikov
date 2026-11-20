@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Всегда на связи</div>
            <h1>Контакты</h1>
            <p>Основная контактная информация проекта.</p>
        </div>
    </section>

    <section class="container content-section">
        <div class="section-heading contacts-heading">
            <h2>Как с нами связаться</h2>
            <p>Карточки ниже выводятся автоматически на основе массива, переданного из маршрута.</p>
        </div>

        <div class="contacts-grid">
            @forelse ($contacts as $contact)
                <article class="contact-card">
                    <div class="contact-icon" aria-hidden="true">{{ str($contact['title'])->substr(0, 1)->upper() }}</div>
                    <div class="contact-card-content">
                        <h3>{{ $contact['title'] }}</h3>
                        <p class="contact-value">{{ $contact['value'] }}</p>
                        @if (!empty($contact['description']))
                            <p class="contact-description">{{ $contact['description'] }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    Контактная информация пока не добавлена.
                </div>
            @endforelse
        </div>
    </section>
@endsection
