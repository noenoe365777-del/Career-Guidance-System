<?php

declare(strict_types=1);

namespace App\Modules\Auth\Validation\Rules;

interface RuleInterface
{
    public function validate(): ?string;
}