<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;

class MainController extends Controller
{
    /**
     * Главная страница: материалы из public/articles.json.
     */
    public function index(): View
    {
        return view('pages.home', [
            'articles' => $this->articles(),
        ]);
    }

    /**
     * Страница галереи с полным изображением выбранной статьи.
     */
    public function gallery(int $id): View
    {
        $article = collect($this->articles())->firstWhere('id', $id);

        abort_if($article === null, 404);

        return view('pages.gallery', [
            'article' => $article,
        ]);
    }

    /**
     * Статьи из JSON-файла в каталоге public.
     *
     * @return array<int, array{id: int, title: string, description: string, preview_image: string, full_image: string}>
     */
    private function articles(): array
    {
        $path = public_path('articles.json');

        if (! File::exists($path)) {
            return [];
        }

        $articles = File::json($path);

        return is_array($articles) ? $articles : [];
    }
}
