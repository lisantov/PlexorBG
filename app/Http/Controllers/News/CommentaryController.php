<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsCommentaryRequest;
use App\Models\News;
use Illuminate\Http\RedirectResponse;

class CommentaryController extends Controller
{
    public function store(StoreNewsCommentaryRequest $request, News $news): RedirectResponse
    {
        $news->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->string('content')->toString(),
            'parent_id' => $request->filled('parent_id')
                ? $request->string('parent_id')->toString()
                : null,
        ]);

        return to_route('news.show', $news);
    }
}
