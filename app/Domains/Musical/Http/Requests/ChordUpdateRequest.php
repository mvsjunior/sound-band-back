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
        $tones = [
                    'C',
                    'C#',
                    'D',
                    'Eb',
                    'E',
                    'F',
                    'F#',
                    'G',
                    'Ab',
                    'A',
                    'Bb',
                    'B',
                    'Cm',
                    'C#m',
                    'Dm',
                    'Ebm',
                    'Em',
                    'Fm',
                    'F#m',
                    'Gm',
                    'Abm',
                    'Am',
                    'Bbm',
                    'Bm'
        ];
        $tones = join(",",$tones);

        return [
            'id' => 'required|int|exists:chords,id',
            'musicId' => 'required|int|exists:musics,id',
            'tone' => 'required|string|in:'.$tones,
            'version' => 'nullable|string',
            'content' => 'required|string',
        ];
    }
}
