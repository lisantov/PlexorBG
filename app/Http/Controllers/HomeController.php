<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\News;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $latestNews = News::query()
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn (News $news) => [
                'id' => $news->id,
                'title' => $news->title,
                'image_url' => $news->imageUrl(),
                'created_at' => $news->created_at?->toIso8601String(),
            ]);

        return Inertia::render('Home', [
            'latest_news' => $latestNews,
            'top_trainers' => [
                'day' => $this->topTrainers(Carbon::now()->subDay()),
                'week' => $this->topTrainers(Carbon::now()->subWeek()),
                'month' => $this->topTrainers(Carbon::now()->subMonth()),
            ],
            'stats' => [
                'users' => '1 200+',
                'trainers' => '80+',
                'solutions' => '15 000+',
                'discussions' => '3 400+',
            ],
        ]);
    }

    /**
     * @return list<array{id: string, title: string, description: string, likes_count: int}>
     */
    private function topTrainers(Carbon $since): array
    {
        return Task::query()
            ->where('status', TaskStatus::APPROVED)
            ->withCount([
                'likes as period_likes_count' => fn ($query) => $query->where(
                    'created_at',
                    '>=',
                    $since
                ),
            ])
            ->orderByDesc('period_likes_count')
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn (Task $task) => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'likes_count' => (int) $task->period_likes_count,
            ])
            ->all();
    }
}
