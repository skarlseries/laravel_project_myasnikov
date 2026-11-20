<?php

namespace App\Http\Controllers\API;

use App\Events\NewArticleEvent;
use App\Http\Controllers\Controller;
use App\Jobs\VeryLongJob;
use App\Models\Article;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    /** Список новостей с пагинацией. */
    public function index(): JsonResponse
    {
        $articles = Article::query()
            ->latest('published_at')
            ->paginate(4);

        return response()->json([
            'status' => 'success',
            'data' => $articles->items(),
            'links' => [
                'first' => $articles->url(1),
                'last' => $articles->url($articles->lastPage()),
                'prev' => $articles->previousPageUrl(),
                'next' => $articles->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }

    /** Одна новость и только одобренные комментарии. */
    public function show(Article $article): JsonResponse
    {
        $article->load([
            'comments' => function ($query) {
                $query->approved()->with('user')->latest();
            },
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $article,
        ]);
    }

    /** Создание новости. Доступно только модератору согласно ArticlePolicy. */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Article::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'image' => ['nullable', 'string', 'max:255'],
            'published_at' => ['required', 'date'],
        ]);

        $article = Article::create($validated);

        $this->forgetWebArticleIndexPages();

        // Лабораторная №11: онлайн-оповещение через Pusher.
        NewArticleEvent::dispatch(
            articleId: $article->id,
            articleName: $article->title,
            articleUrl: route('articles.show', $article),
        );

        // Лабораторная №12: database-уведомления зарегистрированным читателям.
        $readers = User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'reader'))
            ->get();

        if ($readers->isNotEmpty()) {
            Notification::send(
                $readers,
                new NewArticleNotification($article->id, $article->title)
            );
        }

        // Лабораторная №10: email модератору через очередь.
        $moderator = User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'moderator'))
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->first();

        if ($moderator) {
            VeryLongJob::dispatch($article, $moderator->email);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Новость успешно создана.',
            'data' => $article,
            'notifications' => [
                'readers_notified' => $readers->count(),
                'moderator_email_queued' => (bool) $moderator,
            ],
        ], 201);
    }

    /** Изменение новости. Доступно только модератору. */
    public function update(Request $request, Article $article): JsonResponse
    {
        Gate::authorize('update', $article);

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'min:10'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'published_at' => ['sometimes', 'required', 'date'],
        ]);

        $article->update($validated);
        Cache::flush();

        return response()->json([
            'status' => 'success',
            'message' => 'Новость успешно обновлена. Кэш очищен.',
            'data' => $article->fresh(),
        ]);
    }

    /** Удаление новости. Доступно только модератору. */
    public function destroy(Article $article): JsonResponse
    {
        Gate::authorize('delete', $article);

        $deletedArticleId = $article->getKey();
        $article->delete();
        Cache::flush();

        return response()->json([
            'status' => 'success',
            'message' => 'Новость успешно удалена. Кэш очищен.',
            'data' => [
                'id' => $deletedArticleId,
            ],
        ]);
    }

    /** Сбрасывает кэш Blade-страниц списка, созданный лабораторной №13. */
    private function forgetWebArticleIndexPages(): void
    {
        $registryKey = 'articles.index.cached_page_keys';
        $keys = Cache::get($registryKey, []);

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Cache::forget($registryKey);
    }
}
