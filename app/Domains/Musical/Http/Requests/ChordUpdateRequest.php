<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|int|exists:chords,id',
            'musicId' => 'required|int|exists:musics,id',
            'toneId' => 'required|int|exists:tones,id',
            'version' => 'nullable|string',
            'content' => 'required|string',
        ];
    }
}
