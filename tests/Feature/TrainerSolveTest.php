<?php

namespace Tests\Feature;

use App\Enums\SolutionSessionStatus;
use App\Enums\TaskStatus;
use App\Models\Role;
use App\Models\Solution;
use App\Models\SolutionSession;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerSolveTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_user_can_open_solve_editor(): void
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $task = $this->approvedTask($user);

        $response = $this->actingAs($user)->get(route('trainers.solve', $task));

        $response->assertOk();
        $this->assertDatabaseHas('solution_sessions', [
            'user_id' => $user->id,
            'task_id' => $task->id,
            'status' => SolutionSessionStatus::IN_PROGRESS->value,
        ]);
    }

    public function test_user_can_submit_solution_in_session(): void
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $task = $this->approvedTask($user);
        $session = SolutionSession::query()->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'status' => SolutionSessionStatus::IN_PROGRESS,
            'payload' => '',
        ]);

        $response = $this->actingAs($user)->post(route('trainers.solve.submit', $task), [
            'code' => 'print(42)',
        ]);

        $response->assertRedirect(route('trainers.show', [
            'task' => $task,
            'tab' => 'solutions',
        ]));
        $this->assertDatabaseHas('solutions', [
            'user_id' => $user->id,
            'task_id' => $task->id,
            'session_id' => $session->id,
            'code' => 'print(42)',
        ]);
        $session->refresh();
        $this->assertSame('print(42)', $session->payload);
    }

    public function test_reset_clears_editor_only(): void
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $task = $this->approvedTask($user);
        $session = SolutionSession::query()->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'status' => SolutionSessionStatus::IN_PROGRESS,
            'payload' => 'draft',
        ]);
        Solution::query()->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'session_id' => $session->id,
            'code' => 'saved',
        ]);

        $response = $this->actingAs($user)->post(route('trainers.solve.reset', $task));

        $response->assertRedirect();
        $session->refresh();
        $this->assertSame('', $session->payload);
        $this->assertDatabaseHas('solutions', [
            'session_id' => $session->id,
            'code' => 'saved',
        ]);
    }

    public function test_finish_redirects_to_trainer_page(): void
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $task = $this->approvedTask($user);
        SolutionSession::query()->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'status' => SolutionSessionStatus::IN_PROGRESS,
            'payload' => 'code',
        ]);

        $response = $this->actingAs($user)->post(route('trainers.solve.finish', $task), [
            'code' => 'code',
        ]);

        $response->assertRedirect(route('trainers.show', $task));
    }

    private function approvedTask(User $user): Task
    {
        return Task::query()->create([
            'user_id' => $user->id,
            'title' => 'Sample',
            'description' => 'Desc',
            'content' => 'Write code',
            'reference_code' => '',
            'answer' => '42',
            'status' => TaskStatus::APPROVED,
        ]);
    }
}
