<?php

declare(strict_types=1);

namespace App\Modules\Admin\Application\Services;

use App\Modules\Admin\Infrastructure\AdminDashboardRepository;

class AdminDashboardService
{
    private AdminDashboardRepository $adminDashboardRepository;

    public function __construct(?AdminDashboardRepository $adminDashboardRepository = null)
    {
        $this->adminDashboardRepository = $adminDashboardRepository ?? new AdminDashboardRepository();
    }

    public function getDashboardData(): array
    {
        return [
            'totalUsers' => $this->adminDashboardRepository->getTotalUsers(),
            'totalAssessments' => $this->adminDashboardRepository->getTotalAssessments(),
            'totalQuestions' => $this->adminDashboardRepository->getTotalQuestions(),
            'totalCareers' => $this->adminDashboardRepository->getTotalCareers(),
            'recentActivity' => $this->adminDashboardRepository->getRecentActivity(5),
            'systemStatus' => [
                'database' => $this->adminDashboardRepository->checkDatabaseConnection(),
                'assessmentModule' => $this->adminDashboardRepository->isAssessmentModuleActive(),
                'recommendationModule' => $this->adminDashboardRepository->isRecommendationModuleActive(),
            ],
        ];
    }
}
