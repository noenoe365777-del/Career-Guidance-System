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

    public function getDashboardStats(): array
    {
        return $this->adminDashboardRepository->getDashboardStats();
    }

    public function getRecentUsers(int $limit = 5): array
    {
        return $this->adminDashboardRepository->getRecentUsers($limit);
    }

    public function getRecentSubmissions(int $limit = 5): array
    {
        return $this->adminDashboardRepository->getRecentSubmissions($limit);
    }
}
