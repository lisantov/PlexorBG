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

class TrainerSolutionSocialTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_solution(): void
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $author = User::factory()->create(['role_id' => $role->id]);
        $task = $this->approvedTask($author);
        $solution = $this->solutionFor($author, $task, 'print(1)');

        $response = $this->actingAs($user)->post(
            route('trainers.solutions.like.toggle', $solution),
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('likes', [
            'likeable_type' => Solution::class,
            'likeable_id' => $solution->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_comment_on_solution(): void
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $author = User::factory()->create(['role_id' => $role->id]);
        $task = $this->approvedTask($author);
        $solution = $this->solutionFor($author, $task, 'print(1)');

        $response = $this->actingAs($user)->post(
            route('trainers.solutions.comments.store', $solution),
            ['content' => 'Nice approach'],
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('commentaries', [
            'commentable_type' => Solution::class,
            'commentable_id' => $solution->id,
            'user_id' => $user->id,
            'content' => 'Nice approach',
        ]);
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

    private function solutionFor(User $user, Task $task, string $code): Solution
    {
        $session = SolutionSession::query()->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'status' => SolutionSessionStatus::IN_PROGRESS,
            'payload' => '',
        ]);

        return Solution::query()->create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'session_id' => $session->id,
            'code' => $code,
        ]);
    }
}
