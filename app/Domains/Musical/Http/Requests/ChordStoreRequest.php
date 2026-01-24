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
            'musicId' => 'required|int|exists:musics,id',
            'tone' => 'required|string|in:'.$tones,
            'version' => 'nullable|string',
            'content' => 'required|string',
        ];
    }
}
