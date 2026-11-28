<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solution_sessions', function (Blueprint $table) {
            $table->foreignUuid('user_id')->after('task_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('solution_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
