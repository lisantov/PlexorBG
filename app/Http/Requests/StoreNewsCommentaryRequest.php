<?php

namespace App\Http\Requests;

use App\Models\Commentary;
use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsCommentaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var News $news */
        $news = $this->route('news');

        return [
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => [
                'nullable',
                'uuid',
                Rule::exists(Commentary::class, 'id')->where(function ($query) use ($news) {
                    $query
                        ->where('commentable_type', News::class)
                        ->where('commentable_id', $news->id);
                }),
            ],
        ];
    }
}
