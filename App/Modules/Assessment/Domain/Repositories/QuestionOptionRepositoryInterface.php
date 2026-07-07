<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Repositories;

interface QuestionOptionRepositoryInterface
{
    public function getOptionsForQuestion(int $questionId): array;
}

