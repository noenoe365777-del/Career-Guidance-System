<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Entities;

final class StudentAnswer
{
    public function __construct(
        public int $id,
        public int $studentAssessmentId,
        public int $questionId,
        public string $answerValue,
        public int $scoreAwarded = 0,
        public ?string $createdAt = null
    ) {
    }
}

