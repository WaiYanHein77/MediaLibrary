<?php

namespace App\Request;
use App\Request\FormRequest;

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
}