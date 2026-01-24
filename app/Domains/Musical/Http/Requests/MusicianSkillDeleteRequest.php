<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MusicianSkillDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'musicianId' => [
                'required',
                'integer',
                Rule::exists('musician_skill', 'musician_id')
                    ->where(fn ($query) => $query->where('skill_id', $this->input('skillId'))),
            ],
            'skillId' => 'required|integer',
        ];
    }
}
