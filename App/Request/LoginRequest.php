<?php

namespace App\Request;

use App\DTO\LoginDTO;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required' => true,
                'email' => true,
                'max' => 150
            ],
            'password' => [
                'required' => true,
                'min' => 6
            ]
        ];
    }

    public function toDTO(array $data): LoginDTO
    {
        return new LoginDTO(
            $data['email'],
            $data['password']
        );
    }
}