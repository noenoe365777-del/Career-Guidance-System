<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Repositories;

interface AssessmentRepositoryInterface
{
    public function getAll(): array;

    public function getBySlug(string $slug): ?array;
}

