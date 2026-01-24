<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SkillUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $skillId = $this->input('id', 0);

        return [
            'id' => 'required|integer|exists:skills,id',
            'name' => [
                'required',
                'string',
                'max:100',
                'min:1',
                Rule::unique('skills', 'name')->ignore($skillId),
            ],
        ];
    }
}
