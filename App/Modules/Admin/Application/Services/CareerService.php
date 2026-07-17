<?php

declare(strict_types=1);

namespace App\Modules\Admin\Application\Services;

use App\Modules\Admin\Infrastructure\Persistence\CareerRepository;

class CareerService
{
    private CareerRepository $careerRepository;

    public function __construct(?CareerRepository $careerRepository = null)
    {
        $this->careerRepository = $careerRepository ?? new CareerRepository();
    }

    public function getAllCareers(
        int $page = 1,
        int $perPage = 10,
        string $search = '',
        ?string $educationFilter = null,
        ?string $growthFilter = null,
        ?string $categoryFilter = null,
        ?string $statusFilter = null,
        string $sort = 'az'
    ): array {
        return $this->careerRepository->getAllCareers($page, $perPage, $search, $educationFilter, $growthFilter, $categoryFilter, $statusFilter, $sort);
    }

    public function getCareerById(int $id): ?array
    {
        return $this->careerRepository->getCareerById($id);
    }

    public function getTotalCareers(): int
    {
        return $this->careerRepository->getTotalCareers();
    }

    public function getDistinctEducationLevels(): array
    {
        return $this->careerRepository->getDistinctEducationLevels();
    }

    public function getDistinctGrowthRates(): array
    {
        return $this->careerRepository->getDistinctGrowthRates();
    }

    public function getDistinctPersonalityTypes(): array
    {
        return $this->careerRepository->getDistinctPersonalityTypes();
    }

    public function getDistinctInterestTypes(): array
    {
        return $this->careerRepository->getDistinctInterestTypes();
    }

    public function getDistinctAptitudeTypes(): array
    {
        return $this->careerRepository->getDistinctAptitudeTypes();
    }

    public function getDistinctValuesTypes(): array
    {
        return $this->careerRepository->getDistinctValuesTypes();
    }

    public function getAllSkills(): array
    {
        return $this->careerRepository->getAllSkills();
    }

    public function getDistinctStatuses(): array
    {
        return $this->careerRepository->getDistinctStatuses();
    }

    public function createCareer(array $data): ?int
    {
        return $this->careerRepository->createCareer($data);
    }

    public function updateCareer(int $id, array $data): bool
    {
        return $this->careerRepository->updateCareer($id, $data);
    }

    public function deleteCareer(int $id): bool
    {
        return $this->careerRepository->deleteCareer($id);
    }

    public function getSummaryStats(): array
    {
        return $this->careerRepository->getSummaryStats();
    }

    public function getCareerRecommendationStudents(int $careerId): array
    {
        return $this->careerRepository->getCareerRecommendationStudents($careerId);
    }

    public function getAllRecommendationStudents(): array
    {
        return $this->careerRepository->getAllRecommendationStudents();
    }

    public function getCareerRecommendationAnalytics(int $careerId): array
    {
        return $this->careerRepository->getCareerRecommendationAnalytics($careerId);
    }
}
