<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use Inertia\Inertia;
use Inertia\Response;

class TrainerController extends Controller
{
    public function index(): Response
    {
        $items = Task::query()
            ->where('status', TaskStatus::APPROVED)
            ->latest()
            ->get()
            ->map(fn (Task $task) => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'created_at' => $task->created_at?->toIso8601String(),
            ]);

        return Inertia::render('trainers/Index', [
            'items' => $items,
        ]);
    }

    public function show(Task $task): Response
    {
        if ($task->status !== TaskStatus::APPROVED) {
            abort(404);
        }

        return Inertia::render('trainers/Show', [
            'trainer' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'content' => $task->content,
                'created_at' => $task->created_at?->toIso8601String(),
            ],
        ]);
    }
}
