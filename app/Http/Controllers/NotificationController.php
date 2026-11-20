<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Открывает статью из уведомления текущего пользователя
     * и помечает это уведомление как прочитанное.
     */
    public function open(Request $request, string $notificationId): RedirectResponse
    {
        // Получаем уведомление только из списка уведомлений текущего пользователя.
        $notification = $request->user()
            ->notifications()
            ->whereKey($notificationId)
            ->firstOrFail();

        $articleId = data_get($notification->data, 'article_id');

        // Если статья уже удалена, Laravel вернёт страницу 404.
        $article = Article::findOrFail($articleId);

        $notification->markAsRead();

        return redirect()->route('articles.show', $article);
    }
}
