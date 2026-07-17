<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Application\Services;

use App\Modules\Assessment\Infrastructure\Persistence\StudentAssessmentRepository;
use App\Modules\Dashboard\Infrastructure\Persistence\DashboardRepository;
use App\Modules\Recommendation\Application\Services\RecommendationService;

class DashboardService
{
    private DashboardRepository $dashboardRepository;
    private StudentAssessmentRepository $assessmentRepository;
    private RecommendationService $recommendationService;

    private const ASSESSMENT_SLUGS = ['personality', 'interest', 'aptitude', 'values'];

    public function __construct()
    {
        $this->dashboardRepository = new DashboardRepository();
        $this->assessmentRepository = new StudentAssessmentRepository();
        $this->recommendationService = new RecommendationService();
    }

    public function getDashboardData(int $userId): array
    {
        // Get progress from the same source as Assessment page
        $progress = $this->assessmentRepository->getProgressSummary($userId);

        // Build complete status map for all 4 assessments
        $statusMap = [];
        $completedAssessments = 0;

        foreach (self::ASSESSMENT_SLUGS as $slug) {
            $data = $progress[$slug] ?? null;

            if ($data && ($data['is_completed'] ?? false)) {
                $completedAssessments++;
                $statusMap[$slug] = [
                    'status' => 'completed',
                    'completed_at' => $data['completed_at'] ?? null,
                ];
            } elseif ($data) {
                $statusMap[$slug] = [
                    'status' => $data['status'] ?? 'in_progress',
                    'completed_at' => $data['completed_at'] ?? null,
                ];
            } else {
                // Assessment not started yet
                $statusMap[$slug] = [
                    'status' => 'Locked',
                    'completed_at' => null,
                ];
            }
        }

        $completedAssessments = 0;
        foreach (self::ASSESSMENT_SLUGS as $slug) {
            if (($statusMap[$slug]['status'] ?? '') === 'completed') {
                $completedAssessments++;
            }
        }

        $percentage = $completedAssessments > 0 ? round(($completedAssessments / 4) * 100) : 0;
        $allCompleted = $completedAssessments >= 4;

        $recommendation = $this->dashboardRepository->getRecommendation($userId);

        if ($allCompleted && $recommendation === null) {
            $this->recommendationService->generateForUser($userId);
            $recommendation = $this->dashboardRepository->getRecommendation($userId);
        }

        return [
            'totalAssessments' => 4,
            'completedAssessments' => $completedAssessments,
            'percentage' => $percentage,
            'allCompleted' => $allCompleted,
            'statusMap' => $statusMap,
            'recommendation' => $this->dashboardRepository->getRecommendation($userId),
        ];
    }
}