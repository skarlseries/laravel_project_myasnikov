<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailySiteStatisticsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $reportDate,
        public int $articleViewsCount,
        public int $newCommentsCount,
    ) {}

    public function build(): self
    {
        return $this->subject(
            'Ежедневная статистика сайта за ' . $this->reportDate
        )->view('emails.daily_site_statistics');
    }
}