<?php

namespace App\Http\Requests\Trainers;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolutionCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:200000'],
        ];
    }
}
