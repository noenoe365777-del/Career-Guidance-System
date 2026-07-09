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

    public function getAllQuestions(int $page = 1, int $perPage = 10, string $search = '', ?int $assessmentFilter = null, ?string $typeFilter = null): array
    {
        return $this->questionRepository->getAllQuestions($page, $perPage, $search, $assessmentFilter, $typeFilter);
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

    public function getOptionsByQuestionId(int $questionId): array
    {
        return $this->questionRepository->getOptionsByQuestionId($questionId);
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

    public function getAssessments(): array
    {
        try {
            $pdo = \App\Config\Database::getConnection();
            $stmt = $pdo->query('SELECT assessment_id, title FROM assessments ORDER BY assessment_id ASC');
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException) {
            return [];
        }
    }

    public function getQuestionTypes(): array
    {
        return [
            'single_choice' => 'Single Choice',
            'multiple_choice' => 'Multiple Choice',
            'scale' => 'Scale',
        ];
    }
}
