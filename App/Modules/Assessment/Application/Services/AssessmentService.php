<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Application\Services;

use App\Modules\Assessment\Infrastructure\Persistence\AssessmentRepository;
use App\Modules\Assessment\Infrastructure\Persistence\AssessmentResultTypeRepository;
use App\Modules\Assessment\Infrastructure\Persistence\QuestionRepository;
use App\Modules\Assessment\Infrastructure\Persistence\StudentAssessmentRepository;
use App\Modules\Recommendation\Application\Services\RecommendationService;

class AssessmentService
{
    private AssessmentRepository $assessmentRepository;
    private QuestionRepository $questionRepository;
    private StudentAssessmentRepository $studentAssessmentRepository;
    private AssessmentResultTypeRepository $resultTypeRepository;
    private RecommendationService $recommendationService;

    public function __construct()
    {
        $this->assessmentRepository = new AssessmentRepository();
        $this->questionRepository = new QuestionRepository();
        $this->studentAssessmentRepository = new StudentAssessmentRepository();
        $this->resultTypeRepository = new AssessmentResultTypeRepository();
        $this->recommendationService = new RecommendationService();
    }

    public function getAssessments(?int $userId = null): array
    {
        $catalog = $this->assessmentRepository->getAll();
        $progress = $userId ? $this->studentAssessmentRepository->getProgressSummary($userId) : [];

        return array_map(function (array $assessment) use ($progress): array {
            $slug = $assessment['slug'];
            $userProgress = $progress[$slug] ?? null;
            $total = $assessment['total_questions'] ?? 0;
            $preview = $assessment['preview_questions'] ?? 0;

            return [
                'title' => $assessment['title'],
                'description' => $assessment['description'],
                'questions' => $total > 0 ? $total . ' Questions' : 'Quick review',
                'questions_count' => $total,
                'preview_questions' => $preview,
                'icon' => $this->iconForAssessment($slug),
                'iconBg' => $this->iconBgForAssessment($slug),
                'iconColor' => $this->iconColorForAssessment($slug),
                'button' => $this->buttonClassForAssessment($slug),
                'page' => $slug,
                'slug' => $slug,
                'progress' => $userProgress,
            ];
        }, $catalog);
    }

    public function getAssessmentQuestions(string $slug, bool $previewOnly = false): array
    {
        return $this->questionRepository->getQuestionsBySlug($slug, $previewOnly);
    }

    public function getAssessmentQuestionsByAssessmentId(int $assessmentId, int $limit = 5): array
{
    $questions = $this->questionRepository->getQuestionsByAssessmentId($assessmentId);

    return array_slice($questions, 0, $limit);
}

    public function startAssessment(string $slug, ?int $userId = null): array
    {
        $assessment = $this->assessmentRepository->getBySlug($slug);

        if (!$assessment) {
            return ['success' => false, 'message' => 'Assessment not found.'];
        }

        if ($userId) {
            $existing = $this->studentAssessmentRepository->findForUser($userId, (int)$assessment['id']);
            if (!$existing) {
                $this->studentAssessmentRepository->create($userId, (int)$assessment['id']);
            }
        }

        return ['success' => true, 'assessment' => $assessment, 'guest' => empty($userId)];
    }

    public function submitAssessment(string $slug, array $answers, ?int $userId = null, bool $guest = false): array
    {
        $assessment = $this->assessmentRepository->getBySlug($slug);

        if (!$assessment) {
            return ['success' => false, 'message' => 'Assessment is unavailable right now.'];
        }

        $normalizedAnswers = $this->normalizeAnswers($answers);
        $score = $this->calculateScore($slug, $normalizedAnswers);
        $summary = $this->buildSummary($assessment['title'], $score, count($normalizedAnswers), $guest);
        $type = $this->resultTypeRepository->findType($slug, $score);
        $typeLabel = $type['type_label'] ?? null;

        if ($guest) {
            $_SESSION['guest_assessment'][$slug] = [
                'assessment_id' => $assessment['id'],
                'title' => $assessment['title'],
                'answers' => $normalizedAnswers,
                'score' => $score,
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s'),
                'summary' => $summary,
                'preview' => true,
            ];

            return [
                'success' => true,
                'message' => 'Preview assessment saved. Create an account to unlock the full assessment and personalized recommendations.',
                'guest' => true,
                'score' => $score,
                'summary' => $summary,
            ];
        }

        if (!$userId) {
            return ['success' => false, 'message' => 'You must be logged in to save assessment progress.'];
        }

        $result = $this->studentAssessmentRepository->createOrUpdate($userId, (int)$assessment['id'], $normalizedAnswers, $score, $summary, $slug, $typeLabel);

        $recommendation = null;
        if ($this->studentAssessmentRepository->getCompletedCount($userId) >= 4) {
            $recommendations = $this->recommendationService->generateForUser($userId);
            $recommendation = !empty($recommendations);
            if ($recommendation) {
                $top = $recommendations[0];
                $_SESSION['latest_recommendation'] = [
                    'career_name' => $top->careerName,
                    'match_percent' => $top->matchPercent,
                    'description' => $top->description,
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Assessment submitted successfully. Your progress is now saved.',
            'guest' => false,
            'score' => $result['score'] ?? $score,
            'summary' => $result['summary'] ?? $summary,
            'recommendation' => $recommendation,
        ];
    }

    public function getProgress(?int $userId = null): array
    {
        if (!$userId) {
            return [];
        }

        return $this->studentAssessmentRepository->getProgressSummary($userId);
    }

    public function getGuestProgress(?string $slug = null): array
    {
        $progress = $_SESSION['guest_assessment'] ?? [];

        if ($slug) {
            return $progress[$slug] ?? [];
        }

        return $progress;
    }

    private function normalizeAnswers(array $answers): array
    {
        $normalized = [];
        foreach ($answers as $questionId => $answerValue) {
            if (!is_numeric($questionId) || !is_numeric($answerValue)) {
                continue;
            }

            $normalized[(int)$questionId] = (int)$answerValue;
        }

        return $normalized;
    }

    private function calculateScore(string $slug, array $answers): int
    {
        if (empty($answers)) {
            return 0;
        }

        $values = array_values($answers);
        $average = array_sum($values) / count($values);
        $percentage = (int)round(($average / 5) * 100, 0);

        return max(0, min(100, $percentage));
    }

    private function buildSummary(string $title, int $score, int $questionCount, bool $preview = false): string
    {
        if ($preview) {
            return sprintf('You completed the %s preview with a score of %d/100.', $title, $score);
        }
        return sprintf('You completed %s with a score of %d/100.', $title, $score);
    }

    private function iconForAssessment(string $slug): string
    {
        return match ($slug) {
            'personality' => 'fa-solid fa-brain',
            'interest' => 'fa-solid fa-heart',
            'aptitude' => 'fa-solid fa-chart-line',
            'values' => 'fa-solid fa-bullseye',
            default => 'fa-solid fa-file-lines',
        };
    }

    private function iconBgForAssessment(string $slug): string
    {
        return match ($slug) {
            'personality' => 'bg-blue-100',
            'interest' => 'bg-pink-100',
            'aptitude' => 'bg-green-100',
            'values' => 'bg-orange-100',
            default => 'bg-slate-100',
        };
    }

    private function iconColorForAssessment(string $slug): string
    {
        return match ($slug) {
            'personality' => 'text-blue-600',
            'interest' => 'text-pink-600',
            'aptitude' => 'text-green-600',
            'values' => 'text-orange-500',
            default => 'text-slate-600',
        };
    }

    private function buttonClassForAssessment(string $slug): string
    {
        return match ($slug) {
            'personality' => 'bg-blue-700 hover:bg-blue-800',
            'interest' => 'bg-pink-600 hover:bg-pink-700',
            'aptitude' => 'bg-green-600 hover:bg-green-700',
            'values' => 'bg-orange-500 hover:bg-orange-600',
            default => 'bg-slate-700 hover:bg-slate-800',
        };
    }
}
