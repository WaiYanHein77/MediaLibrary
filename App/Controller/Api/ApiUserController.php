<?php

namespace App\Controller\Api;

use App\Service\UserService;

class ApiUserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // REGISTER API
    public function register()
    {
        header('Content-Type: application/json');

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->userService->register(
            $username,
            $email,
            $password
        );

        echo json_encode([
            'success' => $result
        ]);
    }

    // LOGIN API
    public function login()
    {
        header('Content-Type: application/json');

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userService->login(
            $email,
            $password
        );

        if ($user) {

            $_SESSION['user'] = $user;

            echo json_encode([
                'success' => true,
                'user' => $user
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Invalid login'
            ]);
        }
    }

    // LOGOUT API
    public function logout()
    {
        $_SESSION = [];

        session_destroy();

        echo json_encode([
            'success' => true
        ]);
    }
}