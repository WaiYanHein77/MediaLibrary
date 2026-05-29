<?php

namespace App\Controller;

use App\Request\RegisterRequest;
use App\Request\LoginRequest;
use App\Service\UserService;

class UserController extends BaseController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function register(RegisterRequest $request)
    {
        return $this->form(
            $request,
            fn($dto) => $this->userService->register($dto),
            'user/register',
            'index.php?page=login',
            ['pageTitle' => 'Register']
        );
    }

    public function login(LoginRequest $request)
    {
        return $this->form(
            $request,
            fn($dto) => $this->userService->login($dto),
            'user/login',
            'index.php?page=home',
            ['pageTitle' => 'Login'],
            function ($result) {
                $_SESSION['user'] = $result->data()->toArray();
            }
        );
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('index.php?page=login');
    }
}