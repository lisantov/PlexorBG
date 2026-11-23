<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $userRole = Role::factory()->create([
            'name' => 'User',
        ]);
        $adminRole = Role::factory()->create([
            'name' => 'Admin',
        ]);

        User::factory()->create([
            'role_id' => $adminRole->id,
            'login' => 'admin',
            'email' => 'admin@example.com',
            'password_hash' => 'admin',
        ]);

        User::factory()->create([
            'role_id' => $userRole->id,
            'login' => 'user',
            'email' => 'user@example.com',
            'password_hash' => 'user',
        ]);
    }
}
