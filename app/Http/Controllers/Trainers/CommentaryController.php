<?php

namespace App\Http\Controllers\Trainers;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainerCommentaryRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class CommentaryController extends Controller
{
    public function store(StoreTrainerCommentaryRequest $request, Task $task): RedirectResponse
    {
        if ($task->status !== TaskStatus::APPROVED) {
            abort(404);
        }

        $task->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->string('content')->toString(),
            'parent_id' => $request->filled('parent_id')
                ? $request->string('parent_id')->toString()
                : null,
        ]);

        return to_route('trainers.show', $task);
    }
}
