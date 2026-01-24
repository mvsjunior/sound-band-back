<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicianSkillStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'musicianId' => 'required|integer|exists:musicians,id',
            'skillId' => 'required|integer|exists:skills,id',
        ];
    }
}
