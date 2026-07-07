<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Repositories;

interface QuestionRepositoryInterface
{
    public function getQuestionsBySlug(string $slug): array;
}

