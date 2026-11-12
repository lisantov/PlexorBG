<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commentaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('solution_id')->constrained();
            $table->foreignUuid('user_id')->constrained();
            $table->text('content');
            $table->timestamps();
            $table->unique(['solution_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commentaries');
    }
};
