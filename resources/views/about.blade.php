@extends('layouts.app')

@section('title', 'О нас')

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Информация о проекте</div>
            <h1>О нас</h1>
            <p>Небольшой учебный сайт для знакомства с возможностями Laravel и Blade.</p>
        </div>
    </section>

    <section class="container content-section">
        <article class="prose-card">
            <h2>О проекте</h2>
            <p>
                Данный сайт разработан в рамках практической работы по изучению маршрутизатора
                Laravel и шаблонизатора Blade. Его структура состоит из общего макета и отдельных
                представлений для главной страницы, страницы «О нас» и страницы «Контакты».
            </p>
            <p>
                Общий макет содержит шапку сайта с навигацией, область основного содержимого
                и подвал. Благодаря шаблонизатору Blade повторяющиеся элементы не требуется
                создавать заново для каждой страницы: дочерние представления подключают макет
                с помощью директивы <code>@@extends('layouts.app')</code> и заполняют его секцию
                <code>@@section('content')</code>.
            </p>
            <h2>Используемые технологии</h2>
            <div class="technology-list">
                <span class="technology-chip">Laravel</span>
                <span class="technology-chip">Blade</span>
                <span class="technology-chip">PHP</span>
                <span class="technology-chip">HTML5</span>
                <span class="technology-chip">CSS3</span>
            </div>
        </article>
    </section>
@endsection
