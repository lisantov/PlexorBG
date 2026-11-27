<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('admin/news/Create');
    }

    public function store(StoreNewsRequest $request): RedirectResponse
    {
        $imagePath = $request->hasFile('image')
            ? $this->storeImage($request->file('image'))
            : null;

        News::query()->create([
            'author_id' => $request->user()->id,
            'title' => $request->string('title')->toString(),
            'content' => $request->string('content')->toString(),
            'image_path' => $imagePath,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Новость опубликована.',
        ]);

        return to_route('admin.index', ['tab' => 'news']);
    }

    public function edit(News $news): Response
    {
        return Inertia::render('admin/news/Edit', [
            'news' => [
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
                'image_url' => $news->imageUrl(),
            ],
        ]);
    }

    public function update(UpdateNewsRequest $request, News $news): RedirectResponse
    {
        $data = [
            'title' => $request->string('title')->toString(),
            'content' => $request->string('content')->toString(),
        ];

        if ($request->boolean('remove_image')) {
            $this->deleteImage($news->image_path);
            $data['image_path'] = null;
        } elseif ($request->hasFile('image')) {
            $this->deleteImage($news->image_path);
            $data['image_path'] = $this->storeImage($request->file('image'));
        }

        $news->update($data);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Новость обновлена.',
        ]);

        return to_route('admin.index', ['tab' => 'news']);
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Новость удалена.',
        ]);

        return to_route('admin.index', ['tab' => 'news']);
    }

    private function storeImage(UploadedFile $file): string
    {
        return $file->store('news', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
