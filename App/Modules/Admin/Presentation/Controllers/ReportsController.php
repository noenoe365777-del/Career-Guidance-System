<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\ReportsService;
use App\Shared\Core\Controller;

class ReportsController extends Controller
{
    private ReportsService $reportsService;

    public function __construct(?ReportsService $reportsService = null)
    {
        $this->reportsService = $reportsService ?? new ReportsService();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_reports');

        $summary = $this->reportsService->getSummaryStats();

        $assessmentStats = $this->reportsService->getPerAssessmentStats();
        $topCareers = $this->reportsService->getTopRecommendedCareers(5);
        $educationDistribution = $this->reportsService->getEducationLevelDistribution();
        $reportsGenerated = $this->reportsService->getReportsGeneratedCount();

        $this->view(
            'Admin/Presentation/Views/reports/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Reports',
                'activeMenu' => 'reports',
                'totalStudents' => (int)($summary['total_students'] ?? 0),
                'assessmentCompletions' => (int)($summary['completed_assessments'] ?? 0),
                'totalRecommendations' => (int)($summary['total_recommendations'] ?? 0),
                'reportsGenerated' => $reportsGenerated,
                'assessmentStats' => $assessmentStats,
                'topCareers' => $topCareers,
                'educationDistribution' => $educationDistribution,
            ]
        );
    }
}
