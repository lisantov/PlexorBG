<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Commentary;
use App\Models\Solution;
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

        $tab = $request->string('tab')->toString();
        if (! in_array($tab, ['task', 'solutions', 'comments'], true)) {
            $tab = 'task';
        }

        $flat = Commentary::query()
            ->whereMorphedTo('commentable', $task)
            ->with('user:id,login')
            ->orderBy('created_at')
            ->get();

        $solutions = Solution::query()
            ->where('task_id', $task->id)
            ->with('user:id,login')
            ->withCount('comments')
            ->withLikeMeta($request->user())
            ->latest()
            ->get();

        $commentsBySolution = Commentary::query()
            ->where('commentable_type', Solution::class)
            ->whereIn('commentable_id', $solutions->pluck('id'))
            ->with('user:id,login')
            ->orderBy('created_at')
            ->get()
            ->groupBy('commentable_id');

        $solutions = $solutions->map(function (Solution $solution) use ($commentsBySolution) {
            $flat = $commentsBySolution->get($solution->id, collect());

            return [
                'id' => $solution->id,
                'author' => $solution->user?->login,
                'code' => $solution->code,
                'created_at' => $solution->created_at?->toIso8601String(),
                'comments' => Commentary::toTree($flat),
                'comments_count' => $flat->count(),
                ...$solution->likeProps(),
            ];
        });

        return Inertia::render('trainers/Show', [
            'tab' => $tab,
            'trainer' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'content' => $task->content,
                'created_at' => $task->created_at?->toIso8601String(),
                ...$task->likeProps(),
            ],
            'solutions' => $solutions,
            'solutions_count' => $solutions->count(),
            'comments' => Commentary::toTree($flat),
            'comments_count' => $flat->count(),
        ]);
    }
}
