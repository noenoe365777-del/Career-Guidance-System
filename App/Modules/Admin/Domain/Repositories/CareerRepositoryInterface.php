<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface CareerRepositoryInterface
{
    public function getAllCareers(
        int $page = 1,
        int $perPage = 10,
        string $search = '',
        ?string $educationFilter = null,
        ?string $growthFilter = null,
        ?string $categoryFilter = null,
        ?string $statusFilter = null,
        string $sort = 'az'
    ): array;

    public function getCareerById(int $id): ?array;

    public function getTotalCareers(): int;

    public function getDistinctEducationLevels(): array;

    public function getDistinctGrowthRates(): array;

    public function getDistinctPersonalityTypes(): array;

    public function getDistinctStatuses(): array;

    public function createCareer(array $data): ?int;

    public function updateCareer(int $id, array $data): bool;

    public function deleteCareer(int $id): bool;

    public function getSummaryStats(): array;

    public function getCareerRecommendationStudents(int $careerId): array;

    public function getAllRecommendationStudents(): array;

    public function getCareerRecommendationAnalytics(int $careerId): array;
}
