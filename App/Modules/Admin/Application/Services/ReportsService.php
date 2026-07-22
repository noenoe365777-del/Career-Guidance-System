<?php

declare(strict_types=1);

namespace App\Modules\Admin\Application\Services;

use App\Modules\Admin\Infrastructure\Persistence\ReportsRepository;

class ReportsService
{
    private ReportsRepository $reportsRepository;

    public function __construct(?ReportsRepository $reportsRepository = null)
    {
        $this->reportsRepository = $reportsRepository ?? new ReportsRepository();
    }

    public function getSummaryStats(?string $period = null): array
    {
        return $this->reportsRepository->getSummaryStats($period);
    }

    public function getPerAssessmentStats(?string $period = null): array
    {
        return $this->reportsRepository->getPerAssessmentStats($period);
    }

    public function getTopRecommendedCareers(int $limit = 10, ?string $period = null): array
    {
        return $this->reportsRepository->getTopRecommendedCareers($limit, $period);
    }

    public function getStudentRegistrationTrend(?string $period = null): array
    {
        return $this->reportsRepository->getStudentRegistrationTrend($period);
    }

    public function getEducationLevelDistribution(): array
    {
        return $this->reportsRepository->getEducationLevelDistribution();
    }

    public function getMostCommonResultTypes(): array
    {
        return $this->reportsRepository->getMostCommonResultTypes();
    }

    public function getSummaryStatsForRange(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->reportsRepository->getSummaryStatsForRange($startDate, $endDate);
    }

    public function getActiveUsersToday(): int
    {
        return $this->reportsRepository->getActiveUsersToday();
    }

    public function getAverageAssessmentScore(): float
    {
        return $this->reportsRepository->getAverageAssessmentScore();
    }

    public function getAssessmentCompletionTrend(?string $period = null): array
    {
        return $this->reportsRepository->getAssessmentCompletionTrend($period);
    }

    public function getStudentPerformance(int $limit = 10): array
    {
        return $this->reportsRepository->getStudentPerformance($limit);
    }

    public function getReportsGeneratedCount(): int
    {
        return $this->reportsRepository->getReportsGeneratedCount();
    }

    public function getRecentActivities(int $limit = 10): array
    {
        return $this->reportsRepository->getRecentActivities($limit);
    }

    public function getAssessmentScoreComparison(?string $period = null): array
    {
        return $this->reportsRepository->getAssessmentScoreComparison($period);
    }

    public function getRecentAssessmentActivity(int $limit = 10): array
    {
        return $this->reportsRepository->getRecentAssessmentActivity($limit);
    }

    public function getAssessmentCompletionStats(?string $period = null): array
    {
        return $this->reportsRepository->getAssessmentCompletionStats($period);
    }

    public function getStudentPerformanceSummary(): array
    {
        return $this->reportsRepository->getStudentPerformanceSummary();
    }
}
