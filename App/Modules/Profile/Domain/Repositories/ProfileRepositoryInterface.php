<?php

declare(strict_types=1);

namespace App\Modules\Profile\Domain\Repositories;

interface ProfileRepositoryInterface
{
    /**
     * Get a student's complete profile.
     */
    public function findByUserId(int $userId): ?array;

    /**
     * Update student profile.
     */
    public function updateProfile(
        int $userId,
        ?string $phone,
        ?string $address,
        ?string $dateOfBirth,
        ?int $genderId,
        ?int $educationLevelId,
        ?string $profileImage
    ): bool;

    public function updateProfileImage(int $userId, string $imageName): bool;
}