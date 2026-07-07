<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Application\Services;

use App\Modules\Dashboard\Infrastructure\Persistence\DashboardRepository;

class DashboardService
{
    private DashboardRepository $dashboardRepository;

    public function __construct()
    {
        $this->dashboardRepository = new DashboardRepository();
    }

    /**
     * Get dashboard data
     */
    public function getDashboardData(int $userId): array
    {
        $totalAssessments = 4;

        $completedAssessments =
            $this->dashboardRepository->getCompletedAssessments($userId);

        $percentage = 0;

        if ($totalAssessments > 0) {

            $percentage = round(
                ($completedAssessments / $totalAssessments) * 100
            );

        }

        return [

            'totalAssessments' => $totalAssessments,

            'completedAssessments' => $completedAssessments,

            'percentage' => $percentage,

            'statusMap' =>
                $this->dashboardRepository->getAssessmentStatus($userId),

            'recommendation' =>
                $this->dashboardRepository->getRecommendation($userId)

        ];
    }
}