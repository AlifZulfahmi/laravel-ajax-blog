<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Frontend\TagService;
use App\Http\Services\Frontend\ArticleService;
use App\Http\Services\Frontend\CategoryService;

class ArticleController extends Controller
{
    public function __construct(
        private ArticleService $articleService,
        private CategoryService $categoryService,
        private TagService $tagService
    ) {}

    public function index()
    {
        $keyword = request('keyword');

        if ($keyword) {
            $articles = $this->articleService->search($keyword);
        } else {
            $articles = $this->articleService->all();
        }

        // Tambahkan data kategori untuk side menu
        $categories = $this->categoryService->randomCategory();

        return view('frontend.article.index', [
            'articles' => $articles,
            'keyword' => $keyword ?? null,
            'categories' => $categories // pastikan data kategori tersedia
        ]);
    }


    public function show(string $slug)
    {
        // Ambil artikel berdasarkan slug
        $article = $this->articleService->getFirstBy('slug', $slug, true);

        if ($article == null) {
            return view('frontend.custom-error.404', [
                'url' => url('/article/' . $slug),
            ]);
        }

        // Tambah view
        $article->increment('views');

        // Ambil kategori
        $categories = $this->categoryService->randomCategory();

        // Ambil artikel populer
        $popular_articles = $this->articleService->popularArticles(); // Sesuaikan dengan nama metode di service

        return view('frontend.article.show', [
            'article' => $article,
            'related_articles' => $this->articleService->relatedArticles($article->slug),
            'categories' => $categories, // Tambahkan ini
            'popular_articles' => $popular_articles, // Tambahkan ini
        ]);
    }
}