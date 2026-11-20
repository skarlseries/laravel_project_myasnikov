<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArticlesPageTest extends TestCase
{
    public function test_home_page_shows_articles_from_json(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertViewIs('pages.home');
        $response->assertSee('Тигрица и тигрёнок на снегу');
        $response->assertSee('Синий спорткар Marussia на трассе');
        $response->assertSee('images/preview.jpg');
        $response->assertSee('images/preview_2.jpg');
        $response->assertSee(route('gallery', 1), false);
        $response->assertSee(route('gallery', 2), false);
    }

    public function test_gallery_page_shows_full_image(): void
    {
        $response = $this->get(route('gallery', 1));

        $response->assertOk();
        $response->assertViewIs('pages.gallery');
        $response->assertSee('Тигрица и тигрёнок на снегу');
        $response->assertSee('images/full.jpg');
    }

    public function test_gallery_returns_not_found_for_unknown_article(): void
    {
        $this->get(route('gallery', 99))->assertNotFound();
    }
}
