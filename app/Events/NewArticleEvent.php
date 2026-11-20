<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Широковещательное событие о создании новой статьи.
 * Событие отправляется через очередь Laravel.
 */
class NewArticleEvent implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $articleId,
        public string $articleName,
        public string $articleUrl
    ) {}

    /** Публичный канал доступен всем посетителям сайта. */
    public function broadcastOn(): Channel
    {
        return new Channel('articles');
    }

    /** Имя события для Laravel Echo. */
    public function broadcastAs(): string
    {
        return 'NewArticleEvent';
    }

    /** Данные, передаваемые браузеру. */
    public function broadcastWith(): array
    {
        return [
            'article' => [
                'id' => $this->articleId,
                'name' => $this->articleName,
                'url' => $this->articleUrl,
            ],
        ];
    }
}
