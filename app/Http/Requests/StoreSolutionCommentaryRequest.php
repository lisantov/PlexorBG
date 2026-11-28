<?php

namespace App\Http\Requests;

use App\Models\Commentary;
use App\Models\Solution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSolutionCommentaryRequest extends FormRequest
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
        /** @var Solution $solution */
        $solution = $this->route('solution');

        return [
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => [
                'nullable',
                'uuid',
                Rule::exists(Commentary::class, 'id')->where(function ($query) use ($solution) {
                    $query
                        ->where('commentable_type', Solution::class)
                        ->where('commentable_id', $solution->id);
                }),
            ],
        ];
    }
}
