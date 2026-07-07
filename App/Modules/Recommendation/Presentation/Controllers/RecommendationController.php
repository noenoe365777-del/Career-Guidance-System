<?php

namespace App\Modules\Recommendation\Presentation\Controllers;

use App\Shared\Core\View;
use App\Modules\Recommendation\Application\Services\RecommendationService;

class RecommendationController
{
    public function index(): void
    {
        $user = $this->getAuthenticatedUser();
        $userId = (int)($user['id'] ?? $user['user_id'] ?? 0);
        if ($userId <= 0) {
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        $service = new RecommendationService();
        $recommendation = $service->generateForUser($userId);

        View::render('Recommendation/Presentation/Views/recommendations', [
            'pageTitle' => 'Career Recommendation',
            'recommendation' => $recommendation,
        ]);
    }
}

