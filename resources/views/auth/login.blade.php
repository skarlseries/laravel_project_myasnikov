@extends('layouts.app')

@section('title', 'Авторизация')

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Вход в систему</div>
            <h1>Авторизация</h1>
            <p>Введите ваши учетные данные для получения доступа.</p>
        </div>
    </section>

    <section class="container content-section">
        <div class="prose-card auth-card">
            <h2>Вход в аккаунт</h2>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
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

            <form action="{{ route('login.attempt') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Пароль</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>

                <button class="button button-primary form-submit" type="submit">Войти</button>
            </form>

            <p style="margin-top: 18px; margin-bottom: 0; text-align: center; font-size: 14px;">
                Нет аккаунта? <a class="text-link" href="{{ route('signin.create') }}">Зарегистрироваться</a>
            </p>
        </div>
    </section>
@endsection