<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicianUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->input('id');

        return [
            'id' => 'required|int|exists:musicians,id',
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:150|unique:musicians,email,' . $id,
            'phone' => 'nullable|string|max:30',
            'statusId' => 'required|int|exists:musician_statuses,id',
        ];
    }
}
