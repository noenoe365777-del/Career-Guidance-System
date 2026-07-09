<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Domain\Repositories;

interface RecommendationRepositoryInterface
{
    public function getAllCareers(): array;

    public function getStudentScores(int $userId): ?array;

    public function getEducationLevel(int $userId): ?string;

    public function getExistingRecommendations(int $userId): array;

    public function deleteUserRecommendations(int $userId): void;

    public function saveRecommendation(int $userId, int $careerId, float $matchScore, string $reason): bool;
}
