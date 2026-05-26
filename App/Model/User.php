<?php

namespace App\Model;

class User
{
    public static function rules(): array
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
            ]

        ];
    }
    public static function loginRules(): array
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
