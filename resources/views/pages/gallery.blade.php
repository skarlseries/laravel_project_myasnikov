@extends('layouts.app')

@section('title', $article['title'])

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Галерея</div>
            <h1>{{ $article['title'] }}</h1>
            <p>{{ $article['description'] }}</p>
        </div>
    </section>

    <section class="container content-section">
        <figure class="gallery-card">
            <img src="{{ asset($article['full_image']) }}" alt="{{ $article['title'] }}">
        </figure>
        <a class="text-link gallery-back" href="{{ route('home') }}">← На главную</a>
    </section>
@endsection
