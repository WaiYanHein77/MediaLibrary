<?php

namespace App\Request;

class SuggestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required' => true],
            'email' => ['required' => true, 'email' => true],
            'category' => ['required' => true],
            'title' => ['required' => true]
        ];
    }

    public function toDTO(array $data): array
    {
        return $data; // simple array is fine for email
    }
}