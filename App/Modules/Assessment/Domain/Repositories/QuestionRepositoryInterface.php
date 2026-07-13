<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Repositories;

interface QuestionRepositoryInterface
{
    public function getQuestionsBySlug(string $slug, bool $previewOnly = false): array;

    public function getTotalQuestionCount(int $assessmentId): int;

    public function getPreviewQuestionCount(int $assessmentId): int;
}

