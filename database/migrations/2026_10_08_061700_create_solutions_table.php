<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('task_id')->constrained();
            $table->text('code')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('submitted_at')->nullable();
        });

        Schema::create('solution_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('solution_id')->constrained();
            $table->enum('status', ['IN_PROGRESS', 'CANCELLED', 'FINISHED'])->default('IN_PROGRESS');
            $table->text('payload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
