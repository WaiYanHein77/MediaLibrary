<?php

namespace App\Core;

use App\Exception\BaseException;
use Throwable;

class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleException(Throwable $e): void
    {
        self::render($e);
    }

    public static function handleError($severity, $message, $file, $line): void
    {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error) {
            self::render(new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            ));
        }
    }

    private static function render(Throwable $e): void
{
    $isApi = str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/api')
        || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

    $code = 500;

    if ($e instanceof \App\Exception\BaseException) {
        $tmp = $e->getCode();
        if (is_int($tmp) && $tmp >= 100 && $tmp <= 599) {
            $code = $tmp;
        }
    }

    http_response_code($code);

    $message = $e->getMessage();
    $context = $e instanceof \App\Exception\BaseException ? $e->context() : [];

    error_log($e);

    /* =========================
       API RESPONSE (AUTO JSON)
    ========================= */
    if ($isApi) {
        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => $message,
            'errors' => $context
        ]);
        exit;
    }

    /* =========================
       WEB RESPONSE
    ========================= */
    $view = match ($code) {
        422 => BASE_PATH . '/view/errors/422.php',
        404 => BASE_PATH . '/view/errors/404.php',
        default => BASE_PATH . '/view/errors/500.php',
    };

    require $view;
    exit;
}
}