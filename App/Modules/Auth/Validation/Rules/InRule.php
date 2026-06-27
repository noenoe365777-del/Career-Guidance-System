<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

class InRule implements RuleInterface
{
    public function __construct(
        private string $value,
        private array $allowed
    ) {
    }

    public function validate(): ?string
    {
        if (!in_array($this->value, $this->allowed, true)) {
            return 'Invalid selection.';
        }

        return null;
    }
}