<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface UserRepositoryInterface
{
    public function listUsers(int $page = 1, int $perPage = 10, string $search = '', ?string $statusFilter = null): array;

    public function getTotalUsers(): int;

    public function getUserById(int $id): ?array;

    public function updateUserStatus(int $id, int $statusId): bool;

    public function deleteUser(int $id): bool;
}
