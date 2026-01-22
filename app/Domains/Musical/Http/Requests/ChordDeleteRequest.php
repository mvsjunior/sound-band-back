<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChordDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|int|exists:chords,id',
        ];
    }
}
