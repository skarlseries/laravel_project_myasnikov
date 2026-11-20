<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

// Регистрация и авторизация возвращают JSON и Sanctum Bearer-токен.
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Публичное чтение новостей.
Route::get('/articles', [ArticleController::class, 'index'])->name('api.articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])
    ->whereNumber('article')
    ->middleware('track.article.views')
    ->name('api.articles.show');

// Методы, требующие токен Sanctum.
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::post('/articles', [ArticleController::class, 'store'])->name('api.articles.store');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])
        ->whereNumber('article')
        ->name('api.articles.update');
    Route::patch('/articles/{article}', [ArticleController::class, 'update'])
        ->whereNumber('article')
        ->name('api.articles.patch');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])
        ->whereNumber('article')
        ->name('api.articles.destroy');
});
