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

        $period = $_GET['period'] ?? 'all';
        if (!in_array($period, ['all', 'this_month', 'this_year'], true)) {
            $period = 'all';
        }

        $periodParam = $period === 'all' ? null : $period;

        $summaryStats = $this->reportsService->getSummaryStats($periodParam);
        $assessmentStats = $this->reportsService->getPerAssessmentStats($periodParam);
        $topCareers = $this->reportsService->getTopRecommendedCareers(10, $periodParam);
        $registrationTrend = $this->reportsService->getStudentRegistrationTrend($periodParam);
        $educationDistribution = $this->reportsService->getEducationLevelDistribution();
        $resultTypes = $this->reportsService->getMostCommonResultTypes();

        $this->view(
            'Admin/Presentation/Views/reports/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Reports & Analytics',
                'activeMenu' => 'reports',
                'period' => $period,
                'summaryStats' => $summaryStats,
                'assessmentStats' => $assessmentStats,
                'topCareers' => $topCareers,
                'registrationTrend' => $registrationTrend,
                'educationDistribution' => $educationDistribution,
                'resultTypes' => $resultTypes,
            ]
        );
    }
}
