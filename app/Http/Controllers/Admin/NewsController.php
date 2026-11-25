<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
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

        return to_route('news.index');
    }
}
