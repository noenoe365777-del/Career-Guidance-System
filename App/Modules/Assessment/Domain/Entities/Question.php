<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Entities;

final class Question
{
    public function __construct(
        public int $id,
        public int $assessmentId,
        public string $question,
        public string $type = 'single_choice',
        public int $order = 1
    ) {
    }
}

