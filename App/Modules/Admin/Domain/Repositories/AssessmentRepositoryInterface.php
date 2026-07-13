<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface AssessmentRepositoryInterface
{
    public function getAllAssessments(?string $search = null): array;

    public function getFilteredAssessments(?string $search = null, ?string $status = null, ?string $sort = null): array;

    public function getAssessmentById(int $id): ?array;

    public function getTotalAssessments(): int;

    public function getActiveAssessmentsCount(): int;

    public function getTotalQuestionsCount(): int;

    public function getStudentsCompletedCount(): int;

    public function getStudentsCompletedAllCount(): int;

    public function getAverageCompletionRate(): float;

    public function getStudentCompletionsByAssessment(): array;

    public function getPerAssessmentCompletionData(): array;

    public function getDailyCompletionTrend(int $days = 7): array;

    public function getRecentActivity(int $limit = 5): array;

    public function getRecentCompletedAssessments(int $limit = 5): array;

    public function getAverageScoresByAssessment(): array;

    public function getAverageScore(): float;

    public function getTotalStudentCount(): int;

    public function duplicateAssessment(int $id, string $newTitle): ?array;

    public function updateAssessmentStatus(int $id, string $status): bool;

    public function updateAssessment(int $id, string $title, string $description): bool;
}
