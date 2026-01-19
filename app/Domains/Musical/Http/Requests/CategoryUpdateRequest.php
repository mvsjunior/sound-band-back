<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = isset($this->request->all()['id']) ? $this->request->all()['id'] : 0;
        return [
            'id' => 'required|integer|exists:music_categories,id',
            'name' => 'required|string|max:255|min:1|',Rule::unique('music_categories')->ignore($categoryId)
        ];
    }
}
