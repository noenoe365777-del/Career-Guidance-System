<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

class MinLengthRule implements RuleInterface
{
    public function __construct(
        private string $value,
        private int $length
    ) {
    }

    public function validate(): ?string
    {
        if (strlen($this->value) < $this->length) {
            return "Minimum {$this->length} characters required.";
        }

        return null;
    }
}