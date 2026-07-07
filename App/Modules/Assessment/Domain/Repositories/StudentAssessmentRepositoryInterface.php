<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Repositories;

interface StudentAssessmentRepositoryInterface
{
    public function findForUser(int $userId, int $assessmentId): ?array;

    public function create(int $userId, int $assessmentId): array;

    public function createOrUpdate(int $userId, int $assessmentId, array $answers, int $score, string $summary): array;

    public function getCompletedCount(int $userId): int;

    public function getProgressSummary(int $userId): array;
}

