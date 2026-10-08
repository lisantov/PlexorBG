<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained();
            $table->string('title', length: 255);
            $table->string('description', length: 255);
            $table->text('content');
            $table->text('answer');
            $table->enum('status', ['CREATED', 'APPROVED']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
