<?php

namespace App\Controller;

use App\Request\FormRequest;

abstract class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        $helper = new \App\Helper\ViewHelper();

        foreach ($data as $key => $value) {
            $$key = $value;
        }

        require BASE_PATH . "/view/{$view}.php";
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function input(int $type, string $name, int $filter = FILTER_DEFAULT)
    {
        return filter_input($type, $name, $filter);
    }

    protected function form(
        FormRequest $request,
        callable $action,
        string $view,
        string $successRedirect,
        array $viewData = [],
        ?callable $onSuccess = null
    ) {
        $errors = [];
        $old = $_POST ?? [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $old = $_POST;

            // 1. Validate request
            if (!$request->validate($_POST)) {
                $errors = $request->errors();
            } else {

                // 2. Convert to DTO
                $dto = $request->toDTO($_POST);

                // 3. Execute business logic (global errors handled outside)
                $result = $action($dto);

                // 4. Optional hook
                if ($onSuccess) {
                    $onSuccess($result);
                }

                // 5. Redirect on success
                $this->redirect($successRedirect);
            }
        }

        // 6. Render view with errors + old input
        $this->render($view, array_merge($viewData, [
            'errors' => $errors,
            'old' => $old
        ]));
    }
}