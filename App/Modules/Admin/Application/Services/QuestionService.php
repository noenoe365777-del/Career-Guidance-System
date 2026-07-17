<?php

declare(strict_types=1);

namespace App\Modules\Admin\Application\Services;

use App\Modules\Admin\Infrastructure\Persistence\QuestionRepository;

class QuestionService
{
    private QuestionRepository $questionRepository;

    public function __construct(?QuestionRepository $questionRepository = null)
    {
        $this->questionRepository = $questionRepository ?? new QuestionRepository();
    }

    public function getAllQuestions(int $page = 1, int $perPage = 10, string $search = '', ?string $assessmentFilter = null, ?string $typeFilter = null, ?string $difficultyFilter = null, ?string $statusFilter = null, ?string $sort = null): array
    {
        return $this->questionRepository->getAllQuestions($page, $perPage, $search, $assessmentFilter, $typeFilter, $difficultyFilter, $statusFilter, $sort);
    }

    public function getQuestionById(int $id): ?array
    {
        return $this->questionRepository->getQuestionById($id);
    }

    public function getTotalQuestions(): int
    {
        return $this->questionRepository->getTotalQuestions();
    }

    public function getTotalOptions(): int
    {
        return $this->questionRepository->getTotalOptions();
    }

    public function getQuestionsCountByAssessment(): array
    {
        return $this->questionRepository->getQuestionsCountByAssessment();
    }

    public function getQuestionsCountByType(): array
    {
        return $this->questionRepository->getQuestionsCountByType();
    }

    public function getQuestionsCountByDifficulty(): array
    {
        return $this->questionRepository->getQuestionsCountByDifficulty();
    }

    public function getQuestionsCountByStatus(): array
    {
        return $this->questionRepository->getQuestionsCountByStatus();
    }

    public function getAssessmentSlugMap(): array
    {
        return $this->questionRepository->getAssessmentSlugMap();
    }

    public function getRecentlyAddedCount(int $days = 7): int
    {
        return $this->questionRepository->getRecentlyAddedCount($days);
    }

    public function getRecentQuestionActivity(int $limit = 5): array
    {
        return $this->questionRepository->getRecentQuestionActivity($limit);
    }

    public function getOptionsByQuestionId(int $questionId): array
    {
        return $this->questionRepository->getOptionsByQuestionId($questionId);
    }

    public function hasQuestionColumn(string $columnName): bool
    {
        return $this->questionRepository->hasQuestionColumn($columnName);
    }

    public function createQuestion(array $data): ?int
    {
        return $this->questionRepository->createQuestion($data);
    }

    public function updateQuestion(int $id, array $data): bool
    {
        return $this->questionRepository->updateQuestion($id, $data);
    }

    public function deleteQuestion(int $id): bool
    {
        return $this->questionRepository->deleteQuestion($id);
    }

    public function saveOptions(int $questionId, array $options): bool
    {
        return $this->questionRepository->saveOptions($questionId, $options);
    }

    public function duplicateQuestion(int $id, ?int $targetAssessmentId = null): ?int
    {
        return $this->questionRepository->duplicateQuestion($id, $targetAssessmentId);
    }

    public function bulkDelete(array $ids): int
    {
        return $this->questionRepository->bulkDelete($ids);
    }

    public function getAssessments(): array
    {
        try {
            $pdo = \App\Config\Database::getConnection();
            $stmt = $pdo->query('SELECT assessment_id, title, category FROM assessments ORDER BY assessment_id ASC');
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException) {
            return [];
        }
    }

    public function getQuestionsByCategorySlug(int $page = 1, int $perPage = 10, string $search = '', string $categorySlug = '', ?string $typeFilter = null, ?string $difficultyFilter = null, ?string $statusFilter = null, ?string $sort = null): array
    {
        if ($categorySlug === '' || $categorySlug === 'all') {
            return $this->questionRepository->getAllQuestions($page, $perPage, $search, null, $typeFilter, $difficultyFilter, $statusFilter, $sort);
        }

        $slugMap = $this->getAssessmentSlugMap();
        $assessmentId = $slugMap[$categorySlug] ?? 0;

        if ($assessmentId === 0) {
            $fallbackMap = ['personality' => 1, 'interest' => 2, 'aptitude' => 3, 'values' => 4, 'career_values' => 4];
            $assessmentId = $fallbackMap[$categorySlug] ?? 0;
        }

        if ($assessmentId === 0) {
            return [
                'questions' => [],
                'total' => 0,
                'totalQuestions' => 0,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        return $this->questionRepository->getAllQuestions($page, $perPage, $search, (string)$assessmentId, $typeFilter, $difficultyFilter, $statusFilter, $sort);
    }

    public function getQuestionTypes(): array
    {
        return [
            'single_choice' => 'Single Choice',
            'multiple_choice' => 'Multiple Choice',
            'text' => 'Text',
        ];
    }
}
