<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class CommentModerationController extends Controller
{
    // Список комментариев, ожидающих проверки.
    public function index()
    {
        Gate::authorize('manage-comments');

        $comments = Comment::where('is_approved', false)
            ->with(['user', 'article'])
            ->latest()
            ->paginate(10);

        return view('moderation.comments', [
            'comments' => $comments,
        ]);
    }

    // Одобрение комментария.
    public function approve(Comment $comment)
    {
        Gate::authorize('manage-comments');

        $comment->update([
            'is_approved' => true,
        ]);

        // После одобрения комментарий должен появиться в кэшируемой статье.
        Cache::forget('articles.show.'.$comment->article_id);

        return redirect()
            ->route('moderation.comments.index')
            ->with('success', 'Комментарий успешно одобрен.');
    }

    // Отклонение комментария.
    public function reject(Comment $comment)
    {
        Gate::authorize('manage-comments');

        $articleId = $comment->article_id;
        $comment->delete();

        // Сбрасываем кэш статьи после удаления комментария.
        Cache::forget('articles.show.'.$articleId);

        return redirect()
            ->route('moderation.comments.index')
            ->with('success', 'Комментарий отклонён и удалён.');
    }
}
