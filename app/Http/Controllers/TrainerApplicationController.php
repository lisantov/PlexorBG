<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTrainerApplicationRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TrainerApplicationController extends Controller
{
    public function index(): Response
    {
        $user = request()->user();

        $applications = Task::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [TaskStatus::CREATED, TaskStatus::APPROVED, TaskStatus::REJECTED])
            ->latest()
            ->get()
            ->map(fn (Task $task) => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status->value,
                'created_at' => $task->created_at?->toIso8601String(),
            ]);

        return Inertia::render('trainers/applications/Index', [
            'applications' => $applications,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('trainers/applications/Create');
    }

    public function store(StoreTrainerApplicationRequest $request): RedirectResponse
    {
        Task::query()->create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title')->toString(),
            'description' => $request->string('description')->toString(),
            'content' => $request->string('content')->toString(),
            'reference_code' => $request->string('reference_code')->toString(),
            'answer' => $request->string('answer')->toString(),
            'status' => TaskStatus::CREATED,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Заявка отправлена. Мы рассмотрим её в ближайшее время.',
        ]);

        return to_route('trainer-applications.index');
    }
}
