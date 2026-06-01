<?php

namespace App\Helper;

class ViewHelper
{
    public function error(string $field, array $errors): string
    {
        if (empty($errors[$field])) {
            return '';
        }

        return "<small class='error'>" .
            implode('<br>', $errors[$field]) .
        "</small>";
    }

    public function old(array $old, string $field): string
    {
        return htmlspecialchars($old[$field] ?? '');
    }
}