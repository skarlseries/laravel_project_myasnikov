<?php

namespace App\Http\Controllers;

use App\Events\NewArticleEvent;
use App\Jobs\VeryLongJob;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    private const ARTICLE_INDEX_CACHE_KEYS = 'articles.index.cached_page_keys';

    public function index()
    {
        $perPage = 4;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $cacheKey = "articles.index.page.{$page}";

        // В кэше хранятся только массивы и скалярные значения.
        // Это совместимо с настройкой cache.serializable_classes = false в Laravel 13.
        $cachedPage = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($page, $perPage) {
            $total = Article::count();

            $items = Article::query()
                ->latest('published_at')
                ->forPage($page, $perPage)
                ->get()
                ->map(fn (Article $article) => $article->getAttributes())
                ->all();

            return [
                'items' => $items,
                'total' => $total,
            ];
        });

        $this->registerArticleIndexCacheKey($cacheKey);

        // Восстанавливаем модели для существующего Blade-шаблона и Policy.
        $articles = new LengthAwarePaginator(
            collect($cachedPage['items'])->map(
                fn (array $attributes) => (new Article())->newFromBuilder($attributes)
            ),
            $cachedPage['total'],
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->except('page'),
            ]
        );

        return view('articles.index', [
            'articles' => $articles,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Article::class);

        return view('articles.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Article::class);

        $validated = $request->validate([
            'title'        => 'required|string|min:3|max:255',
            'description'  => 'required|string|min:10',
            'image'        => 'nullable|string|max:255',
            'published_at' => 'required|date',
        ]);

        // 1. Создание и сохранение новости.
        $article = Article::create($validated);

        // 2. Удаление кэша всех ранее запрошенных страниц пагинации.
        $this->forgetArticleIndexPages();

        // 3. Онлайн-уведомление через Pusher (лабораторная №11).
        NewArticleEvent::dispatch(
            articleId: $article->id,
            articleName: $article->title,
            articleUrl: route('articles.show', $article),
        );

        // 4. Database-уведомления всем зарегистрированным читателям (лабораторная №12).
        $readers = User::query()
            ->whereHas('role', function ($query) {
                $query->where('name', 'reader');
            })
            ->get();

        if ($readers->isNotEmpty()) {
            Notification::send(
                $readers,
                new NewArticleNotification($article->id, $article->title)
            );
        }

        // 5. Поиск модератора и постановка email в очередь (лабораторная №10).
        $moderator = User::whereHas('role', function ($query) {
            $query->where('name', 'moderator');
        })->first();

        if ($moderator && $moderator->email) {
            VeryLongJob::dispatch($article, $moderator->email);

            $message = 'Новость успешно добавлена. Уведомления читателям сохранены, письмо модератору поставлено в очередь.';
        } else {
            $message = 'Новость успешно добавлена. Уведомления читателям сохранены, но email модератора не найден.';
        }

        return redirect()
            ->route('articles.index')
            ->with('success', $message);
    }

    public function show(Article $article)
    {
        $cacheKey = "articles.show.{$article->getKey()}";

        // Кэшируем саму статью и прошедшие модерацию комментарии навсегда,
        // пока связанный кэш явно не будет сброшен.
        $cachedArticle = Cache::rememberForever($cacheKey, function () use ($article) {
            $freshArticle = Article::query()
                ->with([
                    'comments' => function ($query) {
                        $query->approved()
                            ->with('user')
                            ->latest();
                    },
                ])
                ->findOrFail($article->getKey());

            return [
                'attributes' => $freshArticle->getAttributes(),
                'comments' => $freshArticle->comments->map(function (Comment $comment) {
                    return [
                        'attributes' => $comment->getAttributes(),
                        'user' => $comment->user
                            ? [
                                'id' => $comment->user->getKey(),
                                'name' => $comment->user->name,
                            ]
                            : null,
                    ];
                })->all(),
            ];
        });

        // Восстанавливаем Eloquent-модели из простых массивов.
        $article->setRawAttributes($cachedArticle['attributes'], true);

        $comments = collect($cachedArticle['comments'])->map(function (array $cachedComment) {
            $comment = (new Comment())->newFromBuilder($cachedComment['attributes']);

            if ($cachedComment['user'] !== null) {
                $user = (new User())->newFromBuilder($cachedComment['user']);
                $comment->setRelation('user', $user);
            } else {
                $comment->setRelation('user', null);
            }

            return $comment;
        });

        $article->setRelation('comments', $comments);

        return view('articles.show', [
            'article' => $article,
        ]);
    }

    public function edit(Article $article)
    {
        Gate::authorize('update', $article);

        return view('articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);

        $validated = $request->validate([
            'title'        => 'required|string|min:3|max:255',
            'description'  => 'required|string|min:10',
            'image'        => 'nullable|string|max:255',
            'published_at' => 'required|date',
        ]);

        $article->update($validated);

        // По заданию очищаем кэш после изменения новости.
        Cache::flush();

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Новость успешно обновлена. Кэш очищен.');
    }

    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();

        // По заданию очищаем кэш после удаления новости.
        Cache::flush();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Новость удалена. Кэш очищен.');
    }

    /** Запоминает все ключи закэшированных страниц списка для последующей очистки. */
    private function registerArticleIndexCacheKey(string $cacheKey): void
    {
        $keys = Cache::get(self::ARTICLE_INDEX_CACHE_KEYS, []);

        if (! in_array($cacheKey, $keys, true)) {
            $keys[] = $cacheKey;
            Cache::forever(self::ARTICLE_INDEX_CACHE_KEYS, $keys);
        }
    }

    /** Удаляет кэш первой и всех посещённых страниц пагинации. */
    private function forgetArticleIndexPages(): void
    {
        $keys = Cache::get(self::ARTICLE_INDEX_CACHE_KEYS, []);

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Cache::forget(self::ARTICLE_INDEX_CACHE_KEYS);
    }
}
