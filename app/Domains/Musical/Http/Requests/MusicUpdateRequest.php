<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicUpdateRequest extends FormRequest
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
        return [
            "id" => "required|int|exists:musics,id",
            "name" => "string|required",
            "artist" => "string|required",
            "lyrics" => "string|required",
            "categoryId" => "int|required|exists:music_categories,id",
            "tagIds" => "sometimes|array",
            "tagIds.*" => "int|exists:tags,id"
        ];
    }
}
