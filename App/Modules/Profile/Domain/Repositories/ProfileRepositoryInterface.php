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
        array $data
    ): bool;
       
public function updatePassword(
    int $userId,
    string $currentPassword,
    string $newPassword
): array;


    public function updateProfileImage(int $userId, string $imageName): bool;

    
}