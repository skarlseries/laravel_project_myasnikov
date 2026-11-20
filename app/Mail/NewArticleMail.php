<?php

namespace App\Mail;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewArticleMail extends Mailable
{
    use Queueable, SerializesModels;

    public Article $article;

    // Передаем в конструктор экземпляр созданной статьи
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    // Реализуем метод build для настройки темы и шаблона письма
    public function build()
    {
        return $this->subject('Добавлена новая статья: ' . $this->article->title)
                    ->view('emails.new_article');
    }
}
