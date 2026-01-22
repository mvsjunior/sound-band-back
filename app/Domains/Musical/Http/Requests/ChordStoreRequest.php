<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChordStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'musicId' => 'required|int|exists:musics,id',
            'toneId' => 'required|int|exists:tones,id',
            'version' => 'nullable|string',
            'content' => 'required|string',
        ];
    }
}
