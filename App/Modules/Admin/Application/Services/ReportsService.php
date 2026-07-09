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
}
