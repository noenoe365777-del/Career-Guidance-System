<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Entities;

final class QuestionOption
{
    public function __construct(
        public int $id,
        public int $questionId,
        public string $text,
        public float $scoreValue = 0.0,
        public int $order = 1
    ) {
    }
}

