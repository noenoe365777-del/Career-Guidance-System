<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Domain\Entities;

final class Assessment
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $slug,
        public int $totalQuestions = 0,
        public string $status = 'active'
    ) {
    }
}

