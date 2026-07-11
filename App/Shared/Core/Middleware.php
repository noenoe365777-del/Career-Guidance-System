<?php

declare(strict_types=1);

namespace App\Shared\Core;

interface Middleware
{
    public function handle(): void;
}
