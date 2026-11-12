<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solution_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('task_id')->constrained();
            $table->enum('status', ['IN_PROGRESS', 'CANCELLED', 'FINISHED'])->default('IN_PROGRESS');
            $table->text('payload');
            $table->timestamps();
        });

        Schema::create('solutions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('task_id')->constrained();
            $table->foreignUuid('session_id')->constrained('solution_sessions');
            $table->text('code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solutions');
        Schema::dropIfExists('solution_sessions');
    }
};
