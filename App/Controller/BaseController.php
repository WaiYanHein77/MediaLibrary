<?php

namespace App\Controller;

abstract class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        require BASE_PATH . "/view/{$view}.php";
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function input(
        int $type,
        string $name,
        int $filter = FILTER_DEFAULT
    ) {
        return filter_input($type, $name, $filter);
    }
}