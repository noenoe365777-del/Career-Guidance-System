<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Presentation\Controllers;

use App\Modules\Assessment\Infrastructure\Persistence\NewAssessmentRepository;
use App\Modules\Recommendation\Application\Services\RecommendationService;
use App\Modules\Recommendation\Infrastructure\Persistence\RecommendationRepository;
use App\Shared\Core\Controller;

class CareerRecommendationController extends Controller
{
    private NewAssessmentRepository $assessmentRepo;
    private RecommendationService $recommendationService;
    private RecommendationRepository $recommendationRepo;

    public function __construct()
    {
        $this->assessmentRepo = new NewAssessmentRepository();
        $this->recommendationService = new RecommendationService();
        $this->recommendationRepo = new RecommendationRepository();
    }

    public function index(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);

        if (!$this->assessmentRepo->allAssessmentsCompleted($userId)) {
            $_SESSION['error'] = 'Complete all assessments before viewing recommendations.';
            $this->redirectTo('student-assessments-v2');
        }

        $recommendations = $this->recommendationService->generateForUser($userId);

        $scores = $this->recommendationRepo->getStudentScores($userId);
        $interpretation = $this->getOverallInterpretation($scores);
        $strengths = $this->getOverallStrengths($scores);
        $growthAreas = $this->getOverallGrowthAreas($scores);

        $this->view(
            'Recommendation/Presentation/Views/career_recommendation',
            [
                'pageTitle' => 'Career Recommendations',
                'recommendations' => $recommendations,
                'hasRecommendations' => !empty($recommendations),
                'user' => $user,
                'layout' => 'dashboard',
                'interpretation' => $interpretation,
                'strengths' => $strengths,
                'growthAreas' => $growthAreas,
            ]
        );
    }

    private function getOverallInterpretation(?array $scores): array
    {
        if (!$scores) {
            return [
                'level' => 'In Progress',
                'text' => 'Complete all four assessments to receive a full career interpretation.',
                'color' => 'slate',
            ];
        }

        $values = array_filter([
            (int)($scores['personality_score'] ?? 0),
            (int)($scores['interest_score'] ?? 0),
            (int)($scores['aptitude_score'] ?? 0),
            (int)($scores['values_score'] ?? 0),
        ], fn($v) => $v > 0);

        if (empty($values)) {
            return [
                'level' => 'In Progress',
                'text' => 'Complete all four assessments to receive a full career interpretation.',
                'color' => 'slate',
            ];
        }

        $avg = array_sum($values) / count($values);

        if ($avg >= 80) {
            return [
                'level' => 'Excellent',
                'text' => 'Your assessment results indicate strong alignment across all areas. You have a clear sense of your personality, interests, abilities, and values, which positions you well for career success.',
                'color' => 'emerald',
            ];
        }
        if ($avg >= 65) {
            return [
                'level' => 'Good',
                'text' => 'You show solid development across your assessments. There are specific areas you can continue to build on to strengthen your career readiness.',
                'color' => 'blue',
            ];
        }
        if ($avg >= 45) {
            return [
                'level' => 'Moderate',
                'text' => 'You have a moderate foundation across your assessments. Focus on developing your weaker areas while leveraging your strengths to guide your career path.',
                'color' => 'amber',
            ];
        }
        return [
            'level' => 'Developing',
            'text' => 'Your results show room for growth across multiple areas. Use this as a starting point to explore your interests, build skills, and gain clarity on your career direction.',
            'color' => 'slate',
        ];
    }

    private function getOverallStrengths(?array $scores): array
    {
        if (!$scores) return [];

        $map = [
            'personality_score' => ['label' => 'Personality', 'items' => ['Strong self-awareness', 'Clear behavioral tendencies']],
            'interest_score' => ['label' => 'Interest', 'items' => ['Diverse range of interests', 'Openness to new experiences']],
            'aptitude_score' => ['label' => 'Aptitude', 'items' => ['Strong analytical thinking', 'Effective problem-solving approach']],
            'values_score' => ['label' => 'Career Values', 'items' => ['Clear personal values', 'Decisions aligned with principles']],
        ];

        $strengths = [];
        foreach ($map as $key => $info) {
            if (((int)($scores[$key] ?? 0)) >= 70) {
                foreach ($info['items'] as $item) {
                    $strengths[] = $item;
                }
            }
        }

        return $strengths;
    }

    private function getOverallGrowthAreas(?array $scores): array
    {
        if (!$scores) return [];

        $map = [
            'personality_score' => ['label' => 'Personality', 'items' => ['Broader self-exploration', 'Seek feedback from peers']],
            'interest_score' => ['label' => 'Interest', 'items' => ['Explore a wider range of activities', 'Step outside your comfort zone']],
            'aptitude_score' => ['label' => 'Aptitude', 'items' => ['Practice complex problem-solving', 'Improve time management']],
            'values_score' => ['label' => 'Career Values', 'items' => ['Reflect on how values align with career goals', 'Explore how values apply in team settings']],
        ];

        $growth = [];
        foreach ($map as $key => $info) {
            if (((int)($scores[$key] ?? 0)) < 60) {
                foreach ($info['items'] as $item) {
                    $growth[] = $item;
                }
            }
        }

        return $growth;
    }
}
