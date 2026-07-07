<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Repositories;

interface StudentAnswerRepositoryInterface
{
    public function saveAnswers(int $studentAssessmentId, array $answers): void;

    public function getAnswers(int $studentAssessmentId): array;
}

