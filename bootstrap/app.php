<?php

use App\Http\Middleware\TrackArticleView;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        // Регистрация собственного middleware (в Laravel 13 Kernel.php отсутствует).
        $middleware->alias([
            'track.article.views' => TrackArticleView::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Кастомный ответ при срабатывании исключения шлюзов (Gate) и политик (Policy).
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            $message = $e->getMessage() ?: 'Доступ запрещён: недостаточно прав для выполнения действия.';

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $message,
                ], 403);
            }

            return redirect()
                ->route('articles.index')
                ->with('error', $message);
        });
    })->create();
