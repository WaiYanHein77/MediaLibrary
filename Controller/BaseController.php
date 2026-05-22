<?php

abstract class BaseController
{
    /**
     * Load a view file
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        require BASE_PATH . "/view/{$view}.php";
    }

    /**
     * Redirect helper
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Get sanitized input
     */
    protected function input(
        int $type,
        string $name,
        int $filter = FILTER_DEFAULT
    ) {
        return filter_input($type, $name, $filter);
    }
}