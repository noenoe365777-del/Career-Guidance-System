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
        if (!in_array($period, ['today', '7d', '30d', 'all'], true)) {
            $period = 'all';
        }

        [$currentStart, $currentEnd] = $this->getDateRange($period);
        [$prevStart, $prevEnd] = $this->getPreviousDateRange($period);

        $current = $this->reportsService->getSummaryStatsForRange($currentStart, $currentEnd);
        $previous = $this->reportsService->getSummaryStatsForRange($prevStart, $prevEnd);

        $summaryStats = $this->buildSummaryStats($current, $previous);

        $assessmentStats = $this->reportsService->getPerAssessmentStats($this->periodToLegacy($period));
        $topCareers = $this->reportsService->getTopRecommendedCareers(10, $this->periodToLegacy($period));
        $registrationTrend = $this->reportsService->getStudentRegistrationTrend($this->periodToLegacy($period));
        $educationDistribution = $this->reportsService->getEducationLevelDistribution();
        $assessmentCompletionTrend = $this->reportsService->getAssessmentCompletionTrend($this->periodToLegacy($period));
        $studentPerformance = $this->reportsService->getStudentPerformance(10);
        $recentActivities = $this->reportsService->getRecentActivities(10);

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
                'assessmentCompletionTrend' => $assessmentCompletionTrend,
                'studentPerformance' => $studentPerformance,
                'recentActivities' => $recentActivities,
            ]
        );
    }

    private function getDateRange(string $period): array
    {
        return match ($period) {
            'today' => [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')],
            '7d' => [date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 23:59:59')],
            '30d' => [date('Y-m-d 00:00:00', strtotime('-30 days')), date('Y-m-d 23:59:59')],
            default => [null, null],
        };
    }

    private function getPreviousDateRange(string $period): array
    {
        return match ($period) {
            'today' => [date('Y-m-d 00:00:00', strtotime('-1 day')), date('Y-m-d 23:59:59', strtotime('-1 day'))],
            '7d' => [date('Y-m-d 00:00:00', strtotime('-14 days')), date('Y-m-d 23:59:59', strtotime('-8 days'))],
            '30d' => [date('Y-m-d 00:00:00', strtotime('-60 days')), date('Y-m-d 23:59:59', strtotime('-31 days'))],
            default => [null, null],
        };
    }

    private function periodToLegacy(string $period): ?string
    {
        return match ($period) {
            'today', '7d', '30d' => null,
            default => null,
        };
    }

    private function buildSummaryStats(array $current, array $previous): array
    {
        $keys = ['total_students', 'completed_assessments', 'total_recommendations', 'avg_completion_rate', 'active_users', 'avg_score'];
        $labels = [
            'total_students' => 'Total Students',
            'completed_assessments' => 'Assessments Completed',
            'total_recommendations' => 'Career Recommendations',
            'avg_completion_rate' => 'Completion Rate',
            'active_users' => 'Active Users Today',
            'avg_score' => 'Avg Score',
        ];
        $icons = [
            'total_students' => 'bi-people',
            'completed_assessments' => 'bi-check-circle',
            'total_recommendations' => 'bi-star',
            'avg_completion_rate' => 'bi-graph-up',
            'active_users' => 'bi-person-activity',
            'avg_score' => 'bi-trophy',
        ];
        $colors = [
            'total_students' => 'indigo',
            'completed_assessments' => 'emerald',
            'total_recommendations' => 'amber',
            'avg_completion_rate' => 'cyan',
            'active_users' => 'violet',
            'avg_score' => 'rose',
        ];
        $formats = [
            'total_students' => 'number',
            'completed_assessments' => 'number',
            'total_recommendations' => 'number',
            'avg_completion_rate' => 'percent',
            'active_users' => 'number',
            'avg_score' => 'decimal',
        ];

        $result = [];
        foreach ($keys as $key) {
            $currVal = (float)($current[$key] ?? 0);
            $prevVal = (float)($previous[$key] ?? 0);
            $change = $prevVal > 0 ? round((($currVal - $prevVal) / $prevVal) * 100, 1) : 0;

            $result[$key] = [
                'value' => $currVal,
                'change' => $change,
                'label' => $labels[$key],
                'icon' => $icons[$key],
                'color' => $colors[$key],
                'format' => $formats[$key],
            ];
        }

        return $result;
    }
}
