<?php

use App\Models\News;
use App\Models\Solution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commentaries', function (Blueprint $table) {
            $table->string('commentable_type')->nullable()->after('id');
            $table->uuid('commentable_id')->nullable()->after('commentable_type');
            $table->foreignUuid('parent_id')->nullable()->after('user_id')
                ->constrained('commentaries')
                ->cascadeOnDelete();
            $table->index(['commentable_type', 'commentable_id']);
        });

        if (Schema::hasColumn('commentaries', 'solution_id')) {
            DB::table('commentaries')
                ->whereNotNull('solution_id')
                ->update([
                    'commentable_type' => Solution::class,
                    'commentable_id' => DB::raw('solution_id'),
                ]);
        }

        if (Schema::hasTable('news_comments')) {
            $rows = DB::table('news_comments')->get();

            foreach ($rows as $row) {
                DB::table('commentaries')->insert([
                    'id' => $row->id,
                    'commentable_type' => News::class,
                    'commentable_id' => $row->news_id,
                    'user_id' => $row->user_id,
                    'parent_id' => null,
                    'content' => $row->content,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]);
            }

            Schema::drop('news_comments');
        }

        if (Schema::hasColumn('commentaries', 'solution_id')) {
            try {
                Schema::table('commentaries', function (Blueprint $table) {
                    $table->dropUnique(['solution_id', 'user_id']);
                });
            } catch (Throwable) {
                //
            }

            Schema::table('commentaries', function (Blueprint $table) {
                $table->dropForeign(['solution_id']);
                $table->dropColumn('solution_id');
            });
        }
    }

    public function down(): void
    {
        Schema::create('news_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('news_id')->constrained('news')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        $newsComments = DB::table('commentaries')
            ->where('commentable_type', News::class)
            ->get();

        foreach ($newsComments as $row) {
            DB::table('news_comments')->insert([
                'id' => $row->id,
                'news_id' => $row->commentable_id,
                'user_id' => $row->user_id,
                'content' => $row->content,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }

        Schema::table('commentaries', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['commentable_type', 'commentable_id', 'parent_id']);
            $table->foreignUuid('solution_id')->nullable()->constrained();
            $table->unique(['solution_id', 'user_id']);
        });

        DB::table('commentaries')
            ->where('commentable_type', Solution::class)
            ->update(['solution_id' => DB::raw('commentable_id')]);

        DB::table('commentaries')->where('commentable_type', News::class)->delete();

        DB::table('commentaries')
            ->whereNull('solution_id')
            ->delete();
    }
};
