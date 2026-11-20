<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_comment_is_saved_as_pending_and_user_sees_notice(): void
    {
        $reader = $this->userWithRole('reader', 'Читатель');
        $article = Article::factory()->create();

        $response = $this->actingAs($reader)->post(
            route('articles.comments.store', $article),
            ['body' => 'Текст нового комментария']
        );

        $response->assertRedirect(route('articles.show', $article));
        $response->assertSessionHas('comment_pending');
        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'user_id' => $reader->id,
            'body' => 'Текст нового комментария',
            'is_approved' => false,
        ]);
    }

    public function test_public_article_page_displays_only_approved_comments(): void
    {
        $reader = $this->userWithRole('reader', 'Читатель');
        $article = Article::factory()->create();

        Comment::create([
            'article_id' => $article->id,
            'user_id' => $reader->id,
            'body' => 'Комментарий после модерации',
            'is_approved' => true,
        ]);
        Comment::create([
            'article_id' => $article->id,
            'user_id' => $reader->id,
            'body' => 'Комментарий, ожидающий модерации',
            'is_approved' => false,
        ]);

        $response = $this->get(route('articles.show', $article));

        $response->assertOk();
        $response->assertSee('Комментарий после модерации');
        $response->assertDontSee('Комментарий, ожидающий модерации');
    }

    public function test_only_moderator_can_open_moderation_queue(): void
    {
        $reader = $this->userWithRole('reader', 'Читатель');

        $this->actingAs($reader)
            ->get(route('moderation.comments.index'))
            ->assertRedirect(route('articles.index'));
    }

    public function test_moderator_can_approve_pending_comment(): void
    {
        $moderator = $this->userWithRole('moderator', 'Модератор');
        $reader = $this->userWithRole('reader', 'Читатель');
        $article = Article::factory()->create();
        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $reader->id,
            'body' => 'Комментарий для одобрения',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($moderator)
            ->post(route('moderation.comments.approve', $comment));

        $response->assertRedirect(route('moderation.comments.index'));
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'is_approved' => true,
        ]);
    }

    public function test_moderator_can_reject_and_remove_pending_comment(): void
    {
        $moderator = $this->userWithRole('moderator', 'Модератор');
        $reader = $this->userWithRole('reader', 'Читатель');
        $article = Article::factory()->create();
        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $reader->id,
            'body' => 'Комментарий для отклонения',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($moderator)
            ->delete(route('moderation.comments.reject', $comment));

        $response->assertRedirect(route('moderation.comments.index'));
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    private function userWithRole(string $name, string $title): User
    {
        $role = Role::firstOrCreate(['name' => $name], ['title' => $title]);

        return User::factory()->create(['role_id' => $role->id]);
    }
}
