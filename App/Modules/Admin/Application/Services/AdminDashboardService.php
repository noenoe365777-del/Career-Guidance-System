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
            'totalStudents' => $this->adminDashboardRepository->getTotalStudents(),
            'totalAssessments' => $this->adminDashboardRepository->getTotalAssessments(),
            'totalCareers' => $this->adminDashboardRepository->getTotalCareers(),
            'totalRecommendations' => $this->adminDashboardRepository->getTotalRecommendations(),
            'recentActivity' => $this->adminDashboardRepository->getRecentActivity(5),
        ];
    }
}
