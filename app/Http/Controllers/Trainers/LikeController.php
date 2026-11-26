<?php

namespace App\Http\Controllers\Trainers;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Task $task): RedirectResponse
    {
        if ($task->status !== TaskStatus::APPROVED) {
            abort(404);
        }

        $user = $request->user();

        $existing = $task->likes()->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
        } else {
            $task->likes()->create(['user_id' => $user->id]);
        }

        return back();
    }
}
