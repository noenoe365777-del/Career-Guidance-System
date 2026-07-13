<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface QuestionRepositoryInterface
{
    public function getAllQuestions(int $page = 1, int $perPage = 10, string $search = '', ?int $assessmentFilter = null, ?string $typeFilter = null, ?string $difficultyFilter = null, ?string $statusFilter = null, ?string $sort = null): array;

    public function getQuestionById(int $id): ?array;

    public function getTotalQuestions(): int;

    public function getTotalOptions(): int;

    public function getQuestionsCountByAssessment(): array;

    public function getRecentlyAddedCount(int $days = 7): int;

    public function getRecentQuestionActivity(int $limit = 5): array;

    public function getOptionsByQuestionId(int $questionId): array;

    public function createQuestion(array $data): ?int;

    public function updateQuestion(int $id, array $data): bool;

    public function deleteQuestion(int $id): bool;

    public function saveOptions(int $questionId, array $options): bool;

    public function duplicateQuestion(int $id, ?int $targetAssessmentId = null): ?int;

    public function bulkDelete(array $ids): int;
}
