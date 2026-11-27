<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('admin/news/Create');
    }

    public function store(StoreNewsRequest $request): RedirectResponse
    {
        News::query()->create([
            'author_id' => $request->user()->id,
            'title' => $request->string('title')->toString(),
            'content' => $request->string('content')->toString(),
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Новость опубликована.',
        ]);

        return to_route('admin.index', ['tab' => 'news']);
    }

    public function edit(News $news): Response
    {
        return Inertia::render('admin/news/Edit', [
            'news' => [
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
            ],
        ]);
    }

    public function update(UpdateNewsRequest $request, News $news): RedirectResponse
    {
        $news->update([
            'title' => $request->string('title')->toString(),
            'content' => $request->string('content')->toString(),
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Новость обновлена.',
        ]);

        return to_route('admin.index', ['tab' => 'news']);
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Новость удалена.',
        ]);

        return to_route('admin.index', ['tab' => 'news']);
    }
}
