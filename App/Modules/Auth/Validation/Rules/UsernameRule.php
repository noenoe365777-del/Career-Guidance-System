<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

class UsernameRule
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function validate(): ?string
    {
        if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $this->username)) {
            return "Username must be 4-20 characters and contain only letters, numbers and underscore.";
        }

        return null;
    }
}