<?php

declare(strict_types=1);

namespace App\Modules\Admin\Domain\Repositories;

interface NotificationRepositoryInterface
{
    public function getAll(int $limit = 20, int $offset = 0, ?string $type = null, ?string $search = null, ?int $recipientId = null, ?string $recipientRole = null): array;

    public function getUnread(int $limit = 20, int $offset = 0, ?int $recipientId = null, ?string $recipientRole = null): array;

    public function getUnreadCount(?int $recipientId = null, ?string $recipientRole = null): int;

    public function getTotalCount(?string $type = null, ?string $search = null, ?int $recipientId = null, ?string $recipientRole = null): int;

    public function getTodayCount(?int $recipientId = null, ?string $recipientRole = null): int;

    public function markAsRead(int $id, ?int $recipientId = null): bool;

    public function markAllAsRead(?int $recipientId = null, ?string $recipientRole = null): bool;

    public function delete(int $id, ?int $recipientId = null): bool;

    public function create(array $data): int;
}
