<?php

declare(strict_types=1);

namespace App\Modules\Profile\Application\Services;

use App\Modules\Profile\Domain\Repositories\ProfileRepositoryInterface;

class ProfileService
{
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Get logged-in user's profile
     */
    public function getProfile(int $userId): ?array
    {
        return $this->profileRepository->findByUserId($userId);
    }


    /**
     * Update profile
     */
   public function updateProfile(
    int $userId,
    array $data
): array {

  $success = $this->profileRepository->updateProfile(
    $userId,
    $data
);

    if (!$success) {
        return [
            'success' => false,
            'errors' => [
                'Failed to update profile.'
            ]
        ];
    }

    return [
        'success' => true
    ];
}

public function updatePassword(
    int $userId,
    array $data
): array {

    if (empty($data['current_password'])) {
        return [
            'success' => false,
            'errors' => [
                'Current password is required.'
            ]
        ];
    }

    if (empty($data['new_password'])) {
        return [
            'success' => false,
            'errors' => [
                'New password is required.'
            ]
        ];
    }

    if ($data['new_password'] !== $data['confirm_password']) {
        return [
            'success' => false,
            'errors' => [
                'New passwords do not match.'
            ]
        ];
    }

    return $this->profileRepository->updatePassword(
        $userId,
        $data['current_password'],
        $data['new_password']
    );
}

    public function updateProfileImage(int $userId, string $imageName): bool
{
    return $this->profileRepository->updateProfileImage(
        $userId,
        $imageName
    );
}
}