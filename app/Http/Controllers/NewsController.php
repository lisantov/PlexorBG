<?php

namespace App\Http\Controllers;

use App\Models\News;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(): Response
    {
        $items = News::query()
            ->with('author:id,login')
            ->latest()
            ->get()
            ->map(fn (News $news) => [
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
                'author' => $news->author?->login,
                'created_at' => $news->created_at?->toIso8601String(),
            ]);

        return Inertia::render('news/Index', [
            'items' => $items,
        ]);
    }
}
