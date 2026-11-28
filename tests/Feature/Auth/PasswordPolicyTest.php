<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_rejects_weak_password(): void
    {
        Role::factory()->create(['name' => 'User']);

        $response = $this->post(route('register'), [
            'login' => 'newbie',
            'email' => 'newbie@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_register_accepts_password_with_letters_and_numbers(): void
    {
        Role::factory()->create(['name' => 'User']);

        $response = $this->post(route('register'), [
            'login' => 'newbie',
            'email' => 'newbie@example.com',
            'password' => 'abc123',
            'password_confirmation' => 'abc123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['login' => 'newbie']);
    }

    public function test_register_rejects_duplicate_login(): void
    {
        Role::factory()->create(['name' => 'User']);
        $this->post(route('register'), [
            'login' => 'taken',
            'email' => 'first@example.com',
            'password' => 'abc123',
            'password_confirmation' => 'abc123',
        ]);

        $response = $this->post(route('register'), [
            'login' => 'taken',
            'email' => 'second@example.com',
            'password' => 'abc123',
            'password_confirmation' => 'abc123',
        ]);

        $response->assertSessionHasErrors('login');
    }
}
