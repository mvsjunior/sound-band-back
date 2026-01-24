<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaylistUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:playlists,id',
            'name' => 'required|string|max:150|min:1',
            'musics' => 'sometimes|array',
            'musics.*.id' => 'required|integer|exists:musics,id',
            'musics.*.position' => 'required|integer|min:1',
        ];
    }
}
