<?php

namespace App\Controller;

use App\Request\RegisterRequest;
use App\Request\LoginRequest;
use App\Service\UserService;

class UserController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function register(RegisterRequest $request)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!$request->validate($_POST)) {
                $errors = $request->errors();
            } else {

                $dto = $request->toDTO($_POST);

                $result = $this->userService->register($dto);

                if ($result->isSuccess()) {
                    header("Location: index.php?page=login");
                    exit;
                }

                $errors = $result->errors();
            }
        }

        require BASE_PATH . '/view/user/register.php';
    }

    public function login(LoginRequest $request)
    {
        $errors = [];
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $old = $_POST;

            if (!$request->validate($_POST)) {
                $errors = $request->errors();
            } else {

                $dto = $request->toDTO($_POST);

                $result = $this->userService->login($dto);

                if ($result->isSuccess()) {

                    $_SESSION['user'] = $result->user()->toArray();

                    header("Location: index.php?page=home");
                    exit;
                }

                $errors = $result->errors();
            }
        }

        require BASE_PATH . '/view/user/login.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        header("Location: index.php?page=login");
        exit;
    }
}