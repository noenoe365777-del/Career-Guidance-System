<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

class RequiredRule implements RuleInterface
{
    public function __construct(
        private string $value,
        private string $field
    ) {
    }

    public function validate(): ?string
    {
        if (trim($this->value) === '') {
            return "{$this->field} is required.";
        }

        return null;
    }
}