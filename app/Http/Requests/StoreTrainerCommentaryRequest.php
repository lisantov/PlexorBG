<?php

namespace App\Http\Requests;

use App\Models\Commentary;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrainerCommentaryRequest extends FormRequest
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
        /** @var Task $task */
        $task = $this->route('task');

        return [
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => [
                'nullable',
                'uuid',
                Rule::exists(Commentary::class, 'id')->where(function ($query) use ($task) {
                    $query
                        ->where('commentable_type', Task::class)
                        ->where('commentable_id', $task->id);
                }),
            ],
        ];
    }
}
