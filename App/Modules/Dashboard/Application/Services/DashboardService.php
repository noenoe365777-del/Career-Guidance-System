<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Application\Services;

use App\Modules\Dashboard\Infrastructure\Persistence\DashboardRepository;
use App\Modules\Recommendation\Application\Services\RecommendationService;

class DashboardService
{
    private DashboardRepository $dashboardRepository;
    private RecommendationService $recommendationService;

    public function __construct()
    {
        $this->dashboardRepository = new DashboardRepository();
        $this->recommendationService = new RecommendationService();
    }

    public function getDashboardData(int $userId): array
    {
        $totalAssessments = 4;
        $completedAssessments = $this->dashboardRepository->getCompletedAssessments($userId);
        $allCompleted = $completedAssessments >= $totalAssessments;

        $percentage = 0;
        if ($totalAssessments > 0) {
            $percentage = round(($completedAssessments / $totalAssessments) * 100);
        }

        $recommendation = $this->dashboardRepository->getRecommendation($userId);

        if ($allCompleted && $recommendation === null) {
            $this->recommendationService->generateForUser($userId);
            $recommendation = $this->dashboardRepository->getRecommendation($userId);
        }

        return [
            'totalAssessments' => $totalAssessments,
            'completedAssessments' => $completedAssessments,
            'percentage' => $percentage,
            'allCompleted' => $allCompleted,
            'statusMap' => $this->dashboardRepository->getAssessmentStatus($userId),
            'recommendation' => $recommendation,
        ];
    }
}