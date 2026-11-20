@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <section class="page-hero">
        <div class="container page-hero-content">
            <div class="eyebrow">Авторизация</div>
            <h1>Регистрация</h1>
            <p>Заполните форму ниже, чтобы создать учетную запись и отправить данные.</p>
        </div>
    </section>

    <section class="container content-section">
        <div class="prose-card auth-card">
            <h2>Создание аккаунта</h2>

            @if ($errors->any())
                <div class="form-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('signin.registration') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">Имя</label>
                    <input
                        class="form-control"
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Введите ваше имя"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input
                        class="form-control"
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="example@mail.ru"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Пароль</label>
                    <input
                        class="form-control"
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Минимум 6 символов"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Подтверждение пароля</label>
                    <input
                        class="form-control"
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Повторите пароль"
                        required
                    >
                </div>

                <button class="button button-primary form-submit" type="submit">
                    Зарегистрироваться
                </button>
            </form>
        </div>
    </section>
@endsection
