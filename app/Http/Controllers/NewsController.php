<?php

namespace App\Http\Controllers;

use App\Models\Commentary;
use App\Models\News;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(): Response
    {
        $items = News::query()
            ->with('author:id,login')
            ->withCount('comments')
            ->latest()
            ->get()
            ->map(fn (News $news) => [
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
                'author' => $news->author?->login,
                'created_at' => $news->created_at?->toIso8601String(),
                'comments_count' => $news->comments_count,
            ]);

        return Inertia::render('news/Index', [
            'items' => $items,
        ]);
    }

    public function show(News $news): Response
    {
        $news->load('author:id,login');

        $flat = Commentary::query()
            ->whereMorphedTo('commentable', $news)
            ->with('user:id,login')
            ->orderBy('created_at')
            ->get();

        return Inertia::render('news/Show', [
            'news' => [
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
                'author' => $news->author?->login,
                'created_at' => $news->created_at?->toIso8601String(),
            ],
            'comments' => Commentary::toTree($flat),
            'comments_count' => $flat->count(),
        ]);
    }
}
