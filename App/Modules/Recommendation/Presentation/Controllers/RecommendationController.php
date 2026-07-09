<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Presentation\Controllers;

use App\Modules\Recommendation\Application\Services\RecommendationService;
use App\Shared\Core\Controller;

class RecommendationController extends Controller
{
    private RecommendationService $recommendationService;

    public function __construct()
    {
        $this->recommendationService = new RecommendationService();
    }

    public function index(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);

        $recommendations = $this->recommendationService->generateForUser($userId);

        $this->view(
            'Recommendation/Presentation/Views/recommendations',
            [
                'pageTitle' => 'Career Recommendations',
                'recommendations' => $recommendations,
                'user' => $user,
                'layout' => 'dashboard',
            ]
        );
    }
}
