<?php

namespace App\Jobs;

use App\Mail\NewArticleMail;
use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class VeryLongJob implements ShouldQueue
{
    use Queueable;

    /**
     * Создание задания с передачей новости и адреса получателя.
     */
    public function __construct(
        public Article $article,
        public string $recipientEmail
    ) {}

    /**
     * Выполнение задания очередью.
     */
    public function handle(): void
    {
        Mail::to($this->recipientEmail)
            ->send(new NewArticleMail($this->article));
    }
}