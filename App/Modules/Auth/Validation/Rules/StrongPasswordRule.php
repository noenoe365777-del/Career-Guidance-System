<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

class StrongPasswordRule
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function validate(): ?string
    {
        if (
            !preg_match(
                '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/',
                $this->password
            )
        ) {
            return "Password must contain uppercase, lowercase and a number.";
        }

        return null;
    }
}