<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation;

abstract class Validator
{
    protected array $errors = [];

 protected function addError(
    string $field,
    ?string $message
): void
{
    if ($message !== null) {
        $this->errors[$field] = $message;
    }
}
    public function errors(): array
    {
        return $this->errors;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }
    

    public function fails(): bool
    {
        return !$this->passes();
    }
    abstract public function validate(array $data): bool;
}