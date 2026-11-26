<?php

namespace App\Http\Controllers;

use App\Models\Commentary;
use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(Request $request): Response
    {
        $items = News::query()
            ->with('author:id,login')
            ->withCount('comments')
            ->withLikeMeta($request->user())
            ->latest()
            ->get()
            ->map(fn (News $news) => [
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
                'author' => $news->author?->login,
                'created_at' => $news->created_at?->toIso8601String(),
                'comments_count' => $news->comments_count,
                ...$news->likeProps(),
            ]);

        return Inertia::render('news/Index', [
            'items' => $items,
        ]);
    }

    public function show(Request $request, News $news): Response
    {
        $news->load('author:id,login');
        $news->loadCount('likes');
        if ($user = $request->user()) {
            $news->setAttribute(
                'liked_by_user',
                $news->likes()->where('user_id', $user->id)->exists()
            );
        }

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
                ...$news->likeProps(),
            ],
            'comments' => Commentary::toTree($flat),
            'comments_count' => $flat->count(),
        ]);
    }
}
