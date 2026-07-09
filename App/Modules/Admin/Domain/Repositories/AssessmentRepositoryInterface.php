<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface AssessmentRepositoryInterface
{
    public function getAllAssessments(?string $search = null): array;

    public function getAssessmentById(int $id): ?array;

    public function getTotalAssessments(): int;

    public function getActiveAssessmentsCount(): int;

    public function getTotalQuestionsCount(): int;

    public function updateAssessmentStatus(int $id, string $status): bool;

    public function updateAssessment(int $id, string $title, string $description): bool;
}
