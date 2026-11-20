<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    // Просмотр списка статей доступен всем (включая гостей)
    public function viewAny(?User $user): bool
    {
        return true;
    }

    // Просмотр конкретной статьи доступен всем
    public function view(?User $user, Article $article): bool
    {
        return true;
    }

    // Создание статьи разрешено только модератору
    public function create(User $user): Response
    {
        return $user->isModerator()
            ? Response::allow()
            : Response::deny('Доступ запрещён: создавать новости может только модератор.');
    }

    // Редактирование статьи разрешено только модератору
    public function update(User $user, Article $article): Response
    {
        return $user->isModerator()
            ? Response::allow()
            : Response::deny('Доступ запрещён: редактировать новости может только модератор.');
    }

    // Удаление статьи разрешено только модератору
    public function delete(User $user, Article $article): Response
    {
        return $user->isModerator()
            ? Response::allow()
            : Response::deny('Доступ запрещён: удалять новости может только модератор.');
    }
}
