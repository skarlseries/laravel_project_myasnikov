<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Проверка задания каждую минуту. Команда защищена от повторной отправки отчёта в течение дня.
Schedule::command('site:send-daily-statistics')
    ->everyMinute()
    ->withoutOverlapping();
