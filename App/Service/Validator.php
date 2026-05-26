<?php

namespace App\Service;

class Validator
{
    private array $errors = [];

    public function validate(
        array $data,
        array $rules
    ): bool {

        foreach ($rules as $field => $fieldRules) {

            $value = trim($data[$field] ?? '');

            /*
             required
            */
            if (
                ($fieldRules['required'] ?? false)
                && empty($value)
            ) {

                $this->errors[$field][] =
                    ucfirst($field)
                    . " is required";

                continue;
            }

            /*
             min length
            */
            if (
                isset($fieldRules['min'])
                && strlen($value)
                < $fieldRules['min']
            ) {

                $this->errors[$field][] =
                    ucfirst($field)
                    . " minimum "
                    . $fieldRules['min']
                    . " chars";
            }

            /*
             max length
            */

            if (
                isset($fieldRules['max'])
                && strlen($value)
                > $fieldRules['max']
            ) {

                $this->errors[$field][] =
                    ucfirst($field)
                    . " maximum "
                    . $fieldRules['max']
                    . " chars";
            }

            /*
             email
            */

            if (
                ($fieldRules['email'] ?? false)
                &&
                !filter_var(
                    $value,
                    FILTER_VALIDATE_EMAIL
                )
            ) {

                $this->errors[$field][] =
                    "Invalid email";
            }

        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}