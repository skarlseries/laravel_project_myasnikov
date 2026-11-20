<?php
namespace App\Console\Commands;

use App\Mail\DailySiteStatisticsMail;
use App\Models\ArticleView;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendDailySiteStatistics extends Command
{
    protected $signature = 'site:send-daily-statistics
        {--force : Повторно отправить отчёт за сегодня}';

    protected $description = 'Отправка ежедневной статистики сайта модераторам';

    public function handle(): int
    {
        $now = now();
        $dateKey = $now->toDateString();

        $sentCacheKey = 'site:daily-statistics:sent:' . $dateKey;

        // Проверяем, отправлялся ли отчёт сегодня
        if (
            !$this->option('force') &&
            Cache::store('file')->has($sentCacheKey)
        ) {
            $this->info('Отчёт за сегодня уже отправлен.');

            return self::SUCCESS;
        }

        $startOfDay = $now->copy()->startOfDay();
        $endOfDay = $now->copy()->endOfDay();

        // Подсчитываем просмотры новостей за день
        $articleViewsCount = ArticleView::whereBetween(
            'created_at',
            [$startOfDay, $endOfDay]
        )->count();

        // Подсчитываем новые комментарии за день
        $newCommentsCount = Comment::whereBetween(
            'created_at',
            [$startOfDay, $endOfDay]
        )->count();

        // Находим всех модераторов
        $moderators = User::whereHas('role', function ($query) {
            $query->where('name', 'moderator');
        })
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->get();

        if ($moderators->isEmpty()) {
            $this->warn('Модераторы с email не найдены.');

            return self::FAILURE;
        }

        $reportDate = $now->format('d.m.Y');

        // Отправляем статистику каждому модератору
        foreach ($moderators as $moderator) {
            Mail::to($moderator->email)->send(
                new DailySiteStatisticsMail(
                    reportDate: $reportDate,
                    articleViewsCount: $articleViewsCount,
                    newCommentsCount: $newCommentsCount
                )
            );

            $this->line('Отчёт отправлен: ' . $moderator->email);
        }

        // Запоминаем успешную отправку
        if (!$this->option('force')) {
            Cache::store('file')->put(
                $sentCacheKey,
                true,
                $endOfDay
            );
        }

        $this->table(
            ['Показатель', 'Количество'],
            [
                ['Просмотры новостей', $articleViewsCount],
                ['Новые комментарии', $newCommentsCount],
            ]
        );

        $this->info('Ежедневная статистика успешно отправлена.');

        return self::SUCCESS;
    }
}
