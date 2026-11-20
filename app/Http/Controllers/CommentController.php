<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:2|max:2000',
        ]);

        // Новый комментарий сохраняется без подтверждения модератора.
        $article->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'is_approved' => false,
        ]);

        // Сбрасываем кэш страницы статьи, связанный с комментариями.
        Cache::forget('articles.show.'.$article->getKey());

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Комментарий отправлен на проверку модератору.');
    }
}
