<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Repositories;

interface AssessmentResultTypeRepositoryInterface
{
    public function findType(string $slug, int $score): ?array;
}
