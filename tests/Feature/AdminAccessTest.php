<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_is_redirected_from_admin_panel(): void
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertRedirect(route('home'));
    }
}
