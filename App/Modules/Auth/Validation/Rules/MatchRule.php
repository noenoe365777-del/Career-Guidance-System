<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

class MatchRule implements RuleInterface
{
    public function __construct(
        private string $password,
        private string $confirmPassword
    ) {
    }

    public function validate(): ?string
    {
        if ($this->password !== $this->confirmPassword) {
            return 'Passwords do not match.';
        }

        return null;
    }
}