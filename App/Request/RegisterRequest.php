<?php

namespace App\Request;
use App\Request\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            'username' => [
                'required' => true,
                'min' => 3,
                'max' => 100
            ],

            'email' => [
                'required' => true,
                'email' => true,
                'max' => 150
            ],

            'password' => [
                'required' => true,
                'min' => 6,
                'max' => 255
            ],

            'confirm_password' => [
                'required' => true,
                'same' => 'password'
            ]

        ];
    }
}