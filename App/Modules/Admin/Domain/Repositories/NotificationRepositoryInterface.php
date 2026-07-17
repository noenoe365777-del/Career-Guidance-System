<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface NotificationRepositoryInterface
{
    public function getAll(int $limit = 20, int $offset = 0, ?string $type = null, ?string $search = null): array;

    public function getUnread(int $limit = 20, int $offset = 0): array;

    public function getUnreadCount(): int;

    public function getTotalCount(?string $type = null, ?string $search = null): int;

    public function getTodayCount(): int;

    public function markAsRead(int $id): bool;

    public function markAllAsRead(): bool;

    public function delete(int $id): bool;

    public function create(array $data): int;
}
