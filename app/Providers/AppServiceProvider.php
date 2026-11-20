<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Регистрируем политику для модели Article
        Gate::policy(Article::class, ArticlePolicy::class);

        // Хук шлюза: проверяется прежде всех остальных правил и политик
        Gate::before(function (User $user, string $ability) {
            if ($user->isModerator()) {
                return true;
            }

            return null; // Передаём проверку дальше в политики или другие шлюзы
        });

        // Пример шлюза с кастомным ответом (например, для модерации комментариев)
        Gate::define('manage-comments', function (User $user) {
            return $user->role && $user->role->name === 'moderator';
        });
    }
}
