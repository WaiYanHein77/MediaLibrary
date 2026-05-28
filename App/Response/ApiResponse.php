<?php

namespace App\Response;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success'
    ): array {
        return [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function error(
        array $errors = [],
        string $message = 'Error'
    ): array {
        return [
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ];
    }
}