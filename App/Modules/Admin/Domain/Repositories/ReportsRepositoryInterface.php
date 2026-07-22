<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface ReportsRepositoryInterface
{
    public function getSummaryStats(?string $period = null): array;

    public function getPerAssessmentStats(?string $period = null): array;

    public function getTopRecommendedCareers(int $limit = 10, ?string $period = null): array;

    public function getStudentRegistrationTrend(?string $period = null): array;

    public function getEducationLevelDistribution(): array;

    public function getMostCommonResultTypes(): array;

    public function getActiveUsersToday(): int;

    public function getAverageAssessmentScore(): float;

    public function getAssessmentCompletionTrend(?string $period = null): array;

    public function getStudentPerformance(int $limit = 10): array;

    public function getReportsGeneratedCount(): int;

    public function getRecentActivities(int $limit = 10): array;

    public function getAssessmentScoreComparison(?string $period = null): array;

    public function getRecentAssessmentActivity(int $limit = 10): array;

    public function getAssessmentCompletionStats(?string $period = null): array;

    public function getStudentPerformanceSummary(): array;
}
