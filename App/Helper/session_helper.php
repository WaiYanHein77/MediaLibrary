<?php

function session_user(
    string $key,
    $default = null
) {

    return $_SESSION['user'][$key]
        ?? $default;
}