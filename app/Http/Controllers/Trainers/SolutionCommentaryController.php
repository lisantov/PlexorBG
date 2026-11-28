<?php

namespace App\Http\Controllers\Trainers;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSolutionCommentaryRequest;
use App\Models\Solution;
use Illuminate\Http\RedirectResponse;

class SolutionCommentaryController extends Controller
{
    public function store(StoreSolutionCommentaryRequest $request, Solution $solution): RedirectResponse
    {
        $solution->loadMissing('task');

        if ($solution->task->status !== TaskStatus::APPROVED) {
            abort(404);
        }

        $solution->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->string('content')->toString(),
            'parent_id' => $request->filled('parent_id')
                ? $request->string('parent_id')->toString()
                : null,
        ]);

        return back();
    }
}
