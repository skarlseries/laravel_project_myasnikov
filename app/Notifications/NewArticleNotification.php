<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewArticleNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $articleId,
        public string $articleTitle
    ) {}

    /** Определяет канал доставки уведомления. */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** Данные, которые будут сохранены в таблице notifications. */
    public function toDatabase(object $notifiable): array
    {
        return [
            'article_id' => $this->articleId,
            'article_title' => $this->articleTitle,
            'message' => 'Добавлена новая статья',
        ];
    }
}
