<?php

declare(strict_types=1);

namespace App\Modules\Admin\Application\Services;

use App\Modules\Admin\Infrastructure\Persistence\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(?UserRepository $userRepository = null)
    {
        $this->userRepository = $userRepository ?? new UserRepository();
    }

    public function listUsers(int $page = 1, int $perPage = 10, string $search = '', ?string $statusFilter = null, ?string $assessmentStatus = null, ?int $educationLevel = null): array
    {
        return $this->userRepository->listUsers($page, $perPage, $search, $statusFilter, $assessmentStatus, $educationLevel);
    }

    public function getUserDetailForModal(int $id): ?array
    {
        return $this->userRepository->getUserDetailForModal($id);
    }

    public function getEducationLevels(): array
    {
        return $this->userRepository->getEducationLevels();
    }

    public function getTotalUsers(): int
    {
        return $this->userRepository->getTotalUsers();
    }

    public function getUserById(int $id): ?array
    {
        return $this->userRepository->getUserById($id);
    }

    public function toggleUserStatus(int $id): ?array
    {
        $user = $this->userRepository->getUserById($id);
        if (!$user) {
            return null;
        }

        $newStatusId = ((int)$user['status_id'] === 3) ? 4 : 3;
        $this->userRepository->updateUserStatus($id, $newStatusId);

        return $this->userRepository->getUserById($id);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->deleteUser($id);
    }
}
