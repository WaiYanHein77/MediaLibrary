<?php

namespace App\Controller;

use App\Request\FormRequest;

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

    protected function input(int $type, string $name, int $filter = FILTER_DEFAULT)
    {
        return filter_input($type, $name, $filter);
    }

    /**
     * 🔥 UNIVERSAL FORM HANDLER (NO DUPLICATION ANYMORE)
     */
    protected function form(
        FormRequest $request,
        callable $action,
        string $view,
        string $successRedirect,
        array $viewData = [],
        ?callable $onSuccess = null
    ) {
        $errors = [];
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $old = $_POST;

            // 1. Validate
            if (!$request->validate($_POST)) {
                $errors = $request->errors();
            } else {

                // 2. DTO
                $dto = $request->toDTO($_POST);

                // 3. Service call
                $result = $action($dto);

                // 4. Success
                if ($result->isSuccess()) {

                    if ($onSuccess) {
                        $onSuccess($result);
                    }

                    $this->redirect($successRedirect);
                }

                // 5. Service errors
                $errors = $result->errors();
            }
        }

        // 6. Render
        $this->render($view, array_merge($viewData, [
            'errors' => $errors,
            'old' => $old
        ]));
    }
}