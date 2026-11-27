<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Commentary;
use App\Models\Task;
use App\Support\TitleSearch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrainerController extends Controller
{
    public function index(Request $request): Response
    {
        $sort = $request->string('sort')->toString();
        if (! in_array($sort, ['latest', 'likes'], true)) {
            $sort = 'latest';
        }

        $search = trim($request->string('search')->toString());
        if (strlen($search) > 100) {
            $search = mb_substr($search, 0, 100);
        }

        $query = Task::query()
            ->where('status', TaskStatus::APPROVED)
            ->withCount('comments')
            ->withLikeMeta($request->user());

        TitleSearch::applySql($query, $search);

        if ($sort === 'likes') {
            $query->orderByDesc('likes_count')->latest();
        } else {
            $query->latest();
        }

        $items = TitleSearch::filterCollection($query->get(), $search)
            ->map(fn (Task $task) => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'created_at' => $task->created_at?->toIso8601String(),
                'comments_count' => $task->comments_count,
                ...$task->likeProps(),
            ]);

        return Inertia::render('trainers/Index', [
            'items' => $items,
            'sort' => $sort,
            'search' => $search,
        ]);
    }

    public function show(Request $request, Task $task): Response
    {
        if ($task->status !== TaskStatus::APPROVED) {
            abort(404);
        }

        $task->loadCount('likes');
        if ($user = $request->user()) {
            $task->setAttribute(
                'liked_by_user',
                $task->likes()->where('user_id', $user->id)->exists()
            );
        }

        $flat = Commentary::query()
            ->whereMorphedTo('commentable', $task)
            ->with('user:id,login')
            ->orderBy('created_at')
            ->get();

        return Inertia::render('trainers/Show', [
            'trainer' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'content' => $task->content,
                'created_at' => $task->created_at?->toIso8601String(),
                ...$task->likeProps(),
            ],
            'comments' => Commentary::toTree($flat),
            'comments_count' => $flat->count(),
        ]);
    }
}
