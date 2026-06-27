<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

class DateRule implements RuleInterface
{
    public function __construct(
        private string $date
    ) {
    }

    public function validate(): ?string
    {
        if ($this->date === '') {
            return 'Date of birth is required.';
        }

        if (strtotime($this->date) === false) {
            return 'Invalid date.';
        }

        return null;
    }
}