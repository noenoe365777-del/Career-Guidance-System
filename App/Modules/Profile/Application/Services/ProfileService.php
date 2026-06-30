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
        ?string $phone,
        ?string $address,
        ?string $dateOfBirth,
        ?int $genderId,
        ?int $educationLevelId,
        ?string $profileImage
    ): bool {

        return $this->profileRepository->updateProfile(
            $userId,
            $phone,
            $address,
            $dateOfBirth,
            $genderId,
            $educationLevelId,
            $profileImage
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