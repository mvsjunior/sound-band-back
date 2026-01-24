<?php

namespace App\Domains\Musical\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicianStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:150|unique:musicians,email',
            'phone' => 'nullable|string|max:30',
            'statusId' => 'required|int|exists:musician_statuses,id',
        ];
    }
}
