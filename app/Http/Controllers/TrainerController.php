<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Commentary;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrainerController extends Controller
{
    public function index(Request $request): Response
    {
        $items = Task::query()
            ->where('status', TaskStatus::APPROVED)
            ->withCount('comments')
            ->withLikeMeta($request->user())
            ->latest()
            ->get()
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
