<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveTrainerApplicationRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TrainerApplicationController extends Controller
{
    public function show(Task $task): Response
    {
        abort_unless($task->status === TaskStatus::CREATED, 404);

        $task->load('user:id,login,email');

        return Inertia::render('admin/applications/trainers/Show', [
            'application' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'content' => $task->content,
                'reference_code' => $task->reference_code,
                'answer' => $task->answer,
                'author_login' => $task->user?->login,
                'author_email' => $task->user?->email,
                'created_at' => $task->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function approve(ApproveTrainerApplicationRequest $request, Task $task): RedirectResponse
    {
        abort_unless($task->status === TaskStatus::CREATED, 404);

        $updates = ['status' => TaskStatus::APPROVED];

        if ($request->filled('reference_code')) {
            $updates['reference_code'] = $request->string('reference_code')->toString();
        }

        if ($request->filled('answer')) {
            $updates['answer'] = $request->string('answer')->toString();
        }

        $task->update($updates);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Заявка одобрена, тренажёр опубликован.',
        ]);

        return to_route('admin.index', ['tab' => 'applications']);
    }

    public function reject(Task $task): RedirectResponse
    {
        abort_unless($task->status === TaskStatus::CREATED, 404);

        $task->update([
            'status' => TaskStatus::REJECTED,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Заявка отклонена.',
        ]);

        return to_route('admin.index', ['tab' => 'applications']);
    }
}
