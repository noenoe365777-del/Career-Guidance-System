<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Application\Storage;

interface AssessmentStorageInterface
{
    public function getOrCreateAttempt(int $userId, int $assessmentId): array;
    public function getAttempt(int $attemptId): ?array;
    public function getQuestionOrder(int $attemptId): array;
    public function setQuestionOrder(int $attemptId, array $questionIds): void;
    public function getCurrentIndex(int $attemptId): int;
    public function setCurrentIndex(int $attemptId, int $index): void;
    public function saveAnswer(int $attemptId, int $questionId, int $optionId, float $score): void;
    public function getAnswer(int $attemptId, int $questionId): ?array;
    public function getAnsweredCount(int $attemptId): int;
    public function updateAttempt(int $attemptId, array $data): void;
    public function completeAttempt(int $attemptId): array;
    public function destroyAttempt(int $attemptId): void;
    public function getTimeLimit(int $attemptId): int;
    public function getStartedAt(int $attemptId): ?string;
}