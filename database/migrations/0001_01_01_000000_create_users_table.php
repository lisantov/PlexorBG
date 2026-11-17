<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('access_codes');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuidFor(Role::class, 'role_id')->constrained();
            $table->string('login');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
