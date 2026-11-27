<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $tab = $request->string('tab')->toString();
        if (! in_array($tab, ['news', 'trainers'], true)) {
            $tab = 'news';
        }

        $news = News::query()
            ->with('author:id,login')
            ->latest()
            ->get()
            ->map(fn (News $item) => [
                'id' => $item->id,
                'title' => $item->title,
                'author' => $item->author?->login,
                'created_at' => $item->created_at?->toIso8601String(),
            ]);

        $trainers = Task::query()
            ->latest()
            ->get()
            ->map(fn (Task $item) => [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'status' => $item->status->value,
                'created_at' => $item->created_at?->toIso8601String(),
            ]);

        return Inertia::render('admin/Index', [
            'tab' => $tab,
            'news' => $news,
            'trainers' => $trainers,
        ]);
    }
}
