<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface StudentRolePermissionRepositoryInterface
{
    public function getAll(): array;

    public function getByFeatureKey(string $featureKey): ?array;

    public function update(string $featureKey, bool $isEnabled): bool;

    public function isFeatureEnabled(string $featureKey): bool;

    public function getFeatureDefinitions(): array;
}
