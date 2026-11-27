<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_trainer_application(): void
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->post(route('trainer-applications.store'), [
            'title' => 'Sum two numbers',
            'description' => 'Warm-up kata',
            'content' => 'Read a and b, print a+b',
            'reference_code' => 'a, b = map(int, input().split())\nprint(a + b)',
            'answer' => '42',
        ]);

        $response->assertRedirect(route('trainer-applications.index'));

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Sum two numbers',
            'answer' => '42',
            'status' => TaskStatus::CREATED->value,
        ]);
    }

    public function test_admin_cannot_submit_trainer_application(): void
    {
        $adminRole = Role::factory()->create(['name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)->post(route('trainer-applications.store'), [
            'title' => 'Admin kata',
            'description' => 'Should fail',
            'content' => 'Nope',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('tasks', [
            'user_id' => $admin->id,
            'title' => 'Admin kata',
        ]);
    }

    public function test_admin_can_approve_application_without_changes(): void
    {
        $adminRole = Role::factory()->create(['name' => 'Admin']);
        $userRole = Role::factory()->create(['name' => 'User']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $author = User::factory()->create(['role_id' => $userRole->id]);

        $task = Task::query()->create([
            'user_id' => $author->id,
            'title' => 'Pending task',
            'description' => 'Desc',
            'content' => 'Do work',
            'reference_code' => 'print("ok")',
            'answer' => 'ok',
            'status' => TaskStatus::CREATED,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.trainer-applications.approve', $task),
        );

        $response->assertRedirect(route('admin.index', ['tab' => 'applications']));

        $task->refresh();
        $this->assertSame(TaskStatus::APPROVED, $task->status);
        $this->assertSame('print("ok")', $task->reference_code);
        $this->assertSame('ok', $task->answer);
    }

    public function test_admin_can_approve_application_with_edits(): void
    {
        $adminRole = Role::factory()->create(['name' => 'Admin']);
        $userRole = Role::factory()->create(['name' => 'User']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $author = User::factory()->create(['role_id' => $userRole->id]);

        $task = Task::query()->create([
            'user_id' => $author->id,
            'title' => 'Pending task',
            'description' => 'Desc',
            'content' => 'Do work',
            'reference_code' => 'print("ok")',
            'answer' => 'ok',
            'status' => TaskStatus::CREATED,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.trainer-applications.approve', $task),
            [
                'reference_code' => 'print("fixed")',
                'answer' => 'expected output',
            ],
        );

        $response->assertRedirect(route('admin.index', ['tab' => 'applications']));

        $task->refresh();
        $this->assertSame(TaskStatus::APPROVED, $task->status);
        $this->assertSame('print("fixed")', $task->reference_code);
        $this->assertSame('expected output', $task->answer);
    }
}
