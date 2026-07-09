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
}
