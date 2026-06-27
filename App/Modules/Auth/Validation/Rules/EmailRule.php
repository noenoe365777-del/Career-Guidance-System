<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

class EmailRule implements RuleInterface
{
    public function __construct(
        private string $email
    ) {
    }

    public function validate(): ?string
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email address.';
        }

        return null;
    }
}