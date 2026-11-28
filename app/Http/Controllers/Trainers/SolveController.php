<?php

namespace App\Http\Controllers\Trainers;

use App\Enums\SolutionSessionStatus;
use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trainers\FinishSolutionSessionRequest;
use App\Http\Requests\Trainers\StoreSolutionCodeRequest;
use App\Models\Solution;
use App\Models\SolutionSession;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SolveController extends Controller
{
    public function show(Task $task): Response|RedirectResponse
    {
        $this->ensureSolvable($task);

        $user = request()->user();
        abort_if($user === null, 403);

        $session = $this->findOrCreateSession($user, $task);

        return Inertia::render('trainers/Solve', [
            'trainer' => $this->trainerPayload($task),
            'session' => [
                'id' => $session->id,
                'code' => $session->payload,
            ],
        ]);
    }

    public function submit(StoreSolutionCodeRequest $request, Task $task): RedirectResponse
    {
        $this->ensureSolvable($task);

        $user = $request->user();
        $session = $this->activeSessionFor($user, $task);
        $code = $request->string('code')->toString();

        $session->update(['payload' => $code]);

        Solution::query()->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'session_id' => $session->id,
            'code' => $code,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Решение отправлено.',
        ]);

        return to_route('trainers.show', [
            'task' => $task,
            'tab' => 'solutions',
        ]);
    }

    public function finish(FinishSolutionSessionRequest $request, Task $task): RedirectResponse
    {
        $this->ensureSolvable($task);

        $user = $request->user();
        $session = $this->activeSessionFor($user, $task);

        $updates = ['status' => SolutionSessionStatus::FINISHED];
        if ($request->filled('code')) {
            $updates['payload'] = $request->string('code')->toString();
        }
        $session->update($updates);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Сессия завершена.',
        ]);

        return to_route('trainers.show', $task);
    }

    public function reset(Task $task): RedirectResponse
    {
        $this->ensureSolvable($task);

        $user = request()->user();
        $session = $this->activeSessionFor($user, $task);

        $session->update(['payload' => '']);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Код в редакторе сброшен.',
        ]);

        return back();
    }

    private function ensureSolvable(Task $task): void
    {
        abort_unless($task->status === TaskStatus::APPROVED, 404);
    }

    /**
     * @return array<string, mixed>
     */
    private function trainerPayload(Task $task): array
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'content' => $task->content,
        ];
    }

    private function findOrCreateSession(User $user, Task $task): SolutionSession
    {
        $existing = SolutionSession::query()
            ->where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->where('status', SolutionSessionStatus::IN_PROGRESS)
            ->first();

        if ($existing !== null) {
            return $existing;
        }

        return SolutionSession::query()->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'status' => SolutionSessionStatus::IN_PROGRESS,
            'payload' => '',
        ]);
    }

    private function activeSessionFor(User $user, Task $task): SolutionSession
    {
        $session = SolutionSession::query()
            ->where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->where('status', SolutionSessionStatus::IN_PROGRESS)
            ->first();

        abort_if($session === null, 404);

        return $session;
    }
}
