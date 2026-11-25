<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTrainerRequest;
use App\Http\Requests\Admin\UpdateTrainerRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TrainerController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('admin/trainers/Create');
    }

    public function store(StoreTrainerRequest $request): RedirectResponse
    {
        Task::query()->create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title')->toString(),
            'description' => $request->string('description')->toString(),
            'content' => $request->string('content')->toString(),
            'answer' => $request->string('answer')->toString(),
            'status' => TaskStatus::APPROVED,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Тренажёр опубликован.',
        ]);

        return to_route('trainers.index');
    }

    public function edit(Task $task): Response
    {
        return Inertia::render('admin/trainers/Edit', [
            'trainer' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'content' => $task->content,
                'answer' => $task->answer,
            ],
        ]);
    }

    public function update(UpdateTrainerRequest $request, Task $task): RedirectResponse
    {
        $task->update([
            'title' => $request->string('title')->toString(),
            'description' => $request->string('description')->toString(),
            'content' => $request->string('content')->toString(),
            'answer' => $request->string('answer')->toString(),
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Тренажёр обновлён.',
        ]);

        return to_route('trainers.show', $task);
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Тренажёр удалён.',
        ]);

        return to_route('trainers.index');
    }
}
