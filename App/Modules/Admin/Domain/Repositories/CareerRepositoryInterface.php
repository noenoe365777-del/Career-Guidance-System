<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface CareerRepositoryInterface
{
    public function getAllCareers(int $page = 1, int $perPage = 10, string $search = '', ?string $educationFilter = null, ?string $growthFilter = null): array;

    public function getCareerById(int $id): ?array;

    public function getTotalCareers(): int;

    public function getDistinctEducationLevels(): array;

    public function getDistinctGrowthRates(): array;

    public function createCareer(array $data): ?int;

    public function updateCareer(int $id, array $data): bool;

    public function deleteCareer(int $id): bool;
}
