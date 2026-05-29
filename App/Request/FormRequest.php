<?php

namespace App\Request;

use App\Service\Validator;

abstract class FormRequest
{
    protected Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

       // 🔥 ADD THIS (force all requests to implement it)
    abstract public function toDTO(array $data): mixed;

    abstract public function rules(): array;

    public function validate(array $data): bool
    {
        return $this->validator->validate($data, $this->rules());
    }

    public function errors(): array
    {
        return $this->validator->errors();
    }
}