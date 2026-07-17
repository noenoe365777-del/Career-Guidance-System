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
        $completionStats = $this->adminDashboardRepository->getAssessmentCompletionStats();
        $totalStudents = $this->adminDashboardRepository->getTotalStudents();
        $totalAssessments = $this->adminDashboardRepository->getTotalAssessments();
        $totalCareers = $this->adminDashboardRepository->getTotalCareers();
        $totalQuestions = $this->adminDashboardRepository->getTotalQuestions();
        $totalRecommendations = $this->adminDashboardRepository->getTotalRecommendations();

        $activeStudents = $this->adminDashboardRepository->getTotalStudentsActive();
        $todayRegistrations = $this->adminDashboardRepository->getTodayRegistrations();
        $todayCompletions = $this->adminDashboardRepository->getTodayCompletions();

        $totalAttempts = 0;
        $totalCompleted = 0;
        foreach ($completionStats as $stat) {
            $totalAttempts += (int)($stat['total_attempts'] ?? 0);
            $totalCompleted += (int)($stat['completed_count'] ?? 0);
        }
        $overallCompletionRate = $totalAttempts > 0 ? round(($totalCompleted / $totalAttempts) * 100, 1) : 0;

        return [
            'totalStudents' => $totalStudents,
            'totalAssessments' => $totalAssessments,
            'totalCareers' => $totalCareers,
            'totalQuestions' => $totalQuestions,
            'totalRecommendations' => $totalRecommendations,
            'activeStudents' => $activeStudents,
            'todayRegistrations' => $todayRegistrations,
            'todayCompletions' => $todayCompletions,
            'overallCompletionRate' => $overallCompletionRate,
            'recentActivity' => $this->adminDashboardRepository->getRecentActivity(5),
            'recentNotifications' => $this->adminDashboardRepository->getRecentNotifications(10),
            'completionStats' => $completionStats,
            'unreadNotificationCount' => $this->adminDashboardRepository->getUnreadNotificationCount(),
            'totalQuestions' => $totalQuestions,
        ];
    }
}
