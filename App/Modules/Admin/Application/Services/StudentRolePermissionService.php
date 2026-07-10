<?php

declare(strict_types=1);

namespace App\Modules\Admin\Application\Services;

use App\Modules\Admin\Infrastructure\Persistence\StudentRolePermissionRepository;

class StudentRolePermissionService
{
    private StudentRolePermissionRepository $repository;

    public function __construct(?StudentRolePermissionRepository $repository = null)
    {
        $this->repository = $repository ?? new StudentRolePermissionRepository();
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getFeatureDefinitions(): array
    {
        return $this->repository->getFeatureDefinitions();
    }

    public function update(string $featureKey, bool $isEnabled): bool
    {
        return $this->repository->update($featureKey, $isEnabled);
    }

    public function isFeatureEnabled(string $featureKey): bool
    {
        return $this->repository->isFeatureEnabled($featureKey);
    }
}
