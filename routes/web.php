<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentModerationController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/* Главная страница, галерея, информация и контакты. */
Route::get('/', [MainController::class, 'index'])->name('home');

Route::get('/gallery/{id}', [MainController::class, 'gallery'])
    ->whereNumber('id')
    ->name('gallery');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contacts', function () {
    $contacts = [
        [
            'title' => 'Пряники',
            'value' => 'г. Москва, ул. Пряники, 1234',
            'description' => 'Московский Политех.',
        ],
        [
            'title' => 'Телефон',
            'value' => '+7 (900) 123-45-67',
            'description' => 'По этому номеру можно связаться с нами.',
        ],
        [
            'title' => 'Электронная почта',
            'value' => 'hello@example.ru',
            'description' => 'Для вопросов и обратной связи.',
        ],
        [
            'title' => 'Режим работы',
            'value' => 'Пн–Пт: 09:00–18:00',
            'description' => 'Суббота и воскресенье — выходные дни.',
        ],
    ];

    return view('pages.contacts', ['contacts' => $contacts]);
})->name('contacts');

/* Регистрация и вход. */
Route::get('/signin', [AuthController::class, 'create'])->name('signin.create');
Route::post('/signin', [AuthController::class, 'registration'])->name('signin.registration');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

/* Действия, доступные только авторизованным пользователям. */
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Создание, редактирование и удаление дополнительно проверяются ArticlePolicy.
    Route::resource('articles', ArticleController::class)->except(['index', 'show']);

    // Добавление комментария.
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])
        ->name('articles.comments.store');

    // Панель модерации комментариев; право проверяется Gate manage-comments.
    Route::prefix('moderation')->name('moderation.')->group(function () {
        Route::get('/comments', [CommentModerationController::class, 'index'])
            ->name('comments.index');

        Route::post('/comments/{comment}/approve', [CommentModerationController::class, 'approve'])
            ->name('comments.approve');

        Route::delete('/comments/{comment}/reject', [CommentModerationController::class, 'reject'])
            ->name('comments.reject');
    });

    // Переход по database-уведомлению, отметка прочитанным и открытие новости.
    Route::get('/notifications/{notificationId}/open', [NotificationController::class, 'open'])
        ->name('notifications.open');
});

/* Публичный просмотр списка статей и отдельных публикаций. */
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])
    ->middleware('track.article.views')
    ->name('articles.show');
