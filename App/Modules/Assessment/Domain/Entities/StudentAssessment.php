<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Entities;

final class StudentAssessment
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $assessmentId,
        public string $status = 'in_progress',
        public ?string $startedAt = null,
        public ?string $completedAt = null,
        public int $totalScore = 0,
        public int $progressPercent = 0,
        public ?string $summary = null
    ) {
    }
}

