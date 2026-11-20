@extends('layouts.app')

@section('content')

<div class="container">

    <div class="page-header">
        <h1>Модерация комментариев</h1>
        <p>Проверка комментариев перед публикацией на сайте.</p>
    </div>

    {{-- Сообщения --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($comments->isEmpty())
        <div class="empty-state">
            <h3>Нет комментариев на модерации</h3>
            <p>Все поступившие комментарии проверены.</p>
        </div>
    @else

        @foreach ($comments as $comment)

            <div class="moderation-card">

                <div class="moderation-card-header">

                    <div>
                        <strong>
                            {{ $comment->user->name ?? 'Пользователь' }}
                        </strong>

                        <span class="moderation-date">
                            {{ $comment->created_at->format('d.m.Y H:i') }}
                        </span>
                    </div>

                    <span class="moderation-status">
                        Ожидает проверки
                    </span>

                </div>

                <div class="moderation-article">
                    Новость:
                    <a href="{{ route('articles.show', $comment->article) }}">
                        {{ $comment->article->title ?? 'Новость удалена' }}
                    </a>
                </div>

                <div class="moderation-text">
                    {{ $comment->body }}
                </div>

                <div class="moderation-actions">

                    {{-- Одобрение --}}
                    <form
                        action="{{ route('moderation.comments.approve', $comment) }}"
                        method="POST"
                    >
                        @csrf

                        <button type="submit" class="btn-approve">
                            Одобрить
                        </button>
                    </form>

                    {{-- Отклонение --}}
                    <form
                        action="{{ route('moderation.comments.reject', $comment) }}"
                        method="POST"
                        onsubmit="return confirm('Вы действительно хотите отклонить этот комментарий?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn-reject">
                            Отклонить
                        </button>
                    </form>

                </div>

            </div>

        @endforeach

        <div class="pagination-wrapper">
            {{ $comments->links() }}
        </div>

    @endif

</div>

@endsection