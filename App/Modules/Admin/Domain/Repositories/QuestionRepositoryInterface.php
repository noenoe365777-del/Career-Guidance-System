<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface QuestionRepositoryInterface
{
    public function getAllQuestions(int $page = 1, int $perPage = 10, string $search = '', ?int $assessmentFilter = null, ?string $typeFilter = null): array;

    public function getQuestionById(int $id): ?array;

    public function getTotalQuestions(): int;

    public function getTotalOptions(): int;

    public function getOptionsByQuestionId(int $questionId): array;

    public function createQuestion(array $data): ?int;

    public function updateQuestion(int $id, array $data): bool;

    public function deleteQuestion(int $id): bool;

    public function saveOptions(int $questionId, array $options): bool;
}
