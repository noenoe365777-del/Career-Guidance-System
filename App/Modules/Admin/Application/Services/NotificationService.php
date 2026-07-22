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

    public function getAll(int $limit = 20, int $offset = 0, ?string $type = null, ?string $search = null, ?int $recipientId = null, ?string $recipientRole = null): array
    {
        return $this->notificationRepository->getAll($limit, $offset, $type, $search, $recipientId, $recipientRole);
    }

    public function getUnread(int $limit = 20, int $offset = 0, ?int $recipientId = null, ?string $recipientRole = null): array
    {
        return $this->notificationRepository->getUnread($limit, $offset, $recipientId, $recipientRole);
    }

    public function getUnreadCount(?int $recipientId = null, ?string $recipientRole = null): int
    {
        return $this->notificationRepository->getUnreadCount($recipientId, $recipientRole);
    }

    public function getTotalCount(?string $type = null, ?string $search = null, ?int $recipientId = null, ?string $recipientRole = null): int
    {
        return $this->notificationRepository->getTotalCount($type, $search, $recipientId, $recipientRole);
    }

    public function getTodayCount(?int $recipientId = null, ?string $recipientRole = null): int
    {
        return $this->notificationRepository->getTodayCount($recipientId, $recipientRole);
    }

    public function markAsRead(int $id, ?int $recipientId = null): bool
    {
        return $this->notificationRepository->markAsRead($id, $recipientId);
    }

    public function markAllAsRead(?int $recipientId = null, ?string $recipientRole = null): bool
    {
        return $this->notificationRepository->markAllAsRead($recipientId, $recipientRole);
    }

    public function delete(int $id, ?int $recipientId = null): bool
    {
        return $this->notificationRepository->delete($id, $recipientId);
    }

    public function create(array $data): int
    {
        return $this->notificationRepository->create($data);
    }
}
