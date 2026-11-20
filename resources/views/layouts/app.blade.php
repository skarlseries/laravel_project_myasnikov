<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Учебный сайт для изучения маршрутизации Laravel и шаблонизатора Blade">
    <title>@yield('title', 'Мой сайт') | Курсовой проект</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @vite('resources/js/app.js')
</head>
<body>
    <div id="app"></div>

    <div class="site-wrapper">
        <header class="site-header">
            <div class="container header-inner">
                <a class="brand" href="{{ route('home') }}">
                    <span class="brand-mark">L</span>
                    <span>Мой сайт</span>
                </a>

                <nav class="main-nav">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Главная</a>
                    <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">Новости</a>

                    @can('create', App\Models\Article::class)
                        <a class="nav-link {{ request()->routeIs('articles.create') ? 'active' : '' }}" href="{{ route('articles.create') }}">Создать новость</a>
                    @endcan

                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">О нас</a>
                    <a class="nav-link {{ request()->routeIs('contacts') ? 'active' : '' }}" href="{{ route('contacts') }}">Контакты</a>

                    @guest
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Войти</a>
                        <a class="button button-primary" href="{{ route('signin.create') }}">Регистрация</a>
                    @endguest

                    @auth
                        @php
                            $currentUser = Auth::user();
                            $unreadNotificationsCount = $currentUser->unreadNotifications()->count();
                            $unreadNotifications = $currentUser->unreadNotifications()
                                ->latest()
                                ->limit(10)
                                ->get();
                        @endphp

                        <span class="nav-link user-nav-label">
                            {{ $currentUser->name }} ({{ $currentUser->role?->title ?? 'Читатель' }})
                        </span>

                        <details class="notifications-dropdown">
                            <summary class="notifications-trigger">
                                <span>Уведомления</span>
                                <span class="notifications-count">{{ $unreadNotificationsCount }}</span>
                            </summary>

                            <div class="notifications-panel">
                                <div class="notifications-panel-heading">
                                    Непрочитанные уведомления
                                </div>

                                @forelse ($unreadNotifications as $notification)
                                    <a
                                        class="notification-item"
                                        href="{{ route('notifications.open', ['notificationId' => $notification->id]) }}"
                                    >
                                        <span class="notification-item-dot" aria-hidden="true"></span>
                                        <span class="notification-item-content">
                                            <strong>{{ data_get($notification->data, 'article_title', 'Новая статья') }}</strong>
                                            <small>{{ data_get($notification->data, 'message', 'Добавлена новая статья') }}</small>
                                            <small class="notification-item-date">{{ $notification->created_at->format('d.m.Y H:i') }}</small>
                                        </span>
                                    </a>
                                @empty
                                    <p class="notifications-empty">Новых уведомлений нет.</p>
                                @endforelse
                            </div>
                        </details>

                        @can('manage-comments')
                            <a
                                class="nav-link {{ request()->routeIs('moderation.comments.*') ? 'active' : '' }}"
                                href="{{ route('moderation.comments.index') }}"
                            >
                                Модерация комментариев
                            </a>
                        @endcan

                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button class="button button-danger" type="submit">Выйти</button>
                        </form>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="site-main">
            @yield('content')
        </main>

        <footer class="site-footer">
            <div class="container footer-inner">
                <div>
                    <div class="footer-title">Учебный проект на Laravel</div>
                </div>
                <div class="student-info">
                    <span>Выполнил(а): <strong>Мясников Никита Романович</strong></span>
                    <span>Группа: <strong>257-321</strong></span>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
