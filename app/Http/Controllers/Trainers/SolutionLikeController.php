<?php

namespace App\Http\Controllers\Trainers;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Solution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SolutionLikeController extends Controller
{
    public function toggle(Request $request, Solution $solution): RedirectResponse
    {
        $solution->loadMissing('task');

        if ($solution->task->status !== TaskStatus::APPROVED) {
            abort(404);
        }

        $user = $request->user();

        $existing = $solution->likes()->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
        } else {
            $solution->likes()->create(['user_id' => $user->id]);
        }

        return back();
    }
}
