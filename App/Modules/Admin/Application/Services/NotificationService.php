<?php

declare(strict_types=1);

namespace App\Modules\Admin\Application\Services;

use App\Modules\Admin\Domain\Repositories\NotificationRepositoryInterface;
use App\Modules\Admin\Infrastructure\Persistence\NotificationRepository;

class NotificationService
{
    private NotificationRepositoryInterface $notificationRepository;

    public function __construct(?NotificationRepositoryInterface $notificationRepository = null)
    {
        $this->notificationRepository = $notificationRepository ?? new NotificationRepository();
    }

    public function getAll(int $limit = 20, int $offset = 0, ?string $type = null, ?string $search = null): array
    {
        return $this->notificationRepository->getAll($limit, $offset, $type, $search);
    }

    public function getUnread(int $limit = 20, int $offset = 0): array
    {
        return $this->notificationRepository->getUnread($limit, $offset);
    }

    public function getUnreadCount(): int
    {
        return $this->notificationRepository->getUnreadCount();
    }

    public function getTotalCount(?string $type = null, ?string $search = null): int
    {
        return $this->notificationRepository->getTotalCount($type, $search);
    }

    public function getTodayCount(): int
    {
        return $this->notificationRepository->getTodayCount();
    }

    public function markAsRead(int $id): bool
    {
        return $this->notificationRepository->markAsRead($id);
    }

    public function markAllAsRead(): bool
    {
        return $this->notificationRepository->markAllAsRead();
    }

    public function delete(int $id): bool
    {
        return $this->notificationRepository->delete($id);
    }

    public function create(array $data): int
    {
        return $this->notificationRepository->create($data);
    }
}
