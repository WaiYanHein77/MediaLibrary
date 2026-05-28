<?php

namespace App\Service;

class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {

            $value = trim($data[$field] ?? '');

            // required
            if (($fieldRules['required'] ?? false) && empty($value)) {
                $this->errors[$field][] = "{$field} is required";
                continue;
            }

            // min length
            if (isset($fieldRules['min']) && strlen($value) < $fieldRules['min']) {
                $this->errors[$field][] = "{$field} is too short";
            }

            // max length
            if (isset($fieldRules['max']) && strlen($value) > $fieldRules['max']) {
                $this->errors[$field][] = "{$field} is too long";
            }

            // email validation
            if (($fieldRules['email'] ?? false) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field][] = "{$field} must be a valid email";
            }

            // same rule
            if (isset($fieldRules['same'])) {
                $other = $data[$fieldRules['same']] ?? null;
                if ($value !== $other) {
                    $this->errors[$field][] = "{$field} must match {$fieldRules['same']}";
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}